<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] === 'admin') {
        header("Location: ../administrator/index.php");
    }
    if ($_SESSION["uloga"] === 'mod') {
        header("Location: ../moderator/index.php");
    }
}

$idVrsteOglasa = "";
$zastavica1 = 0;

if (isset($_GET["idVrste"])) {
    $idVrsteOglasa = $_GET["idVrste"];
    $zastavica1 = 1;
}

$baza = new Baza();
$baza->spojiDB();

$naziv = "";
$opis = "";
$url = "";
$datum = "";
$name = "";
$sifraVrste = "";
$korisnikID = "";
$zahtjevID = "";
$stranicaID = "";
$zastavica2 = 0;


foreach ($_POST as $key => $value) {
    $sifraVrste = $_POST["sifraVrste"];
    $naziv = $_POST["naziv"];
    $opis = $_POST["opis"];
    $url = $_POST["url"];
    $datum = $_POST["datum"];
    $name = $_FILES['file']['name'];
    $zastavica2 = 1;
}

if ($zastavica2 === 1) {
    $tmp_name = $_FILES['file']['tmp_name'];
    $position = strpos($name, ".");
    $fileextension = substr($name, $position + 1);
    $fileextension = strtolower($fileextension);
    if (isset($name)) {
        $path = "../slike/";
        if (!empty($name)) {
            if (move_uploaded_file($tmp_name, $path . $name)) {
                //echo "Uploadano!";
            }
        }
    }

    $trenutno = date("Y-m-d H:i:s");
    $sql_insert1 = "INSERT INTO `zahtjev`(`datum_i_vrijeme`, `naslov_zahtjeva`, `opsi_zahtjeva`,`idvrste_oglasa`) VALUES('" . $trenutno . "', '" . $naziv . "', '" . $opis . "' , '" . $idVrsteOglasa . "')";
    $sql_insert2 = "INSERT INTO `stranica`(`naziv_stranice`, `url_stranice`, `slika`) VALUES('nova stranica', '" . $url . "', '" . $name . "')";
    $sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
    $sql_upit_zahtjev = "SELECT * FROM zahtjev WHERE naslov_zahtjeva LIKE '" . $naziv . "'";
    $sql_upit_stranica = "SELECT * FROM stranica WHERE url_stranice LIKE '" . $url . "'";
    $baza->selectDB($sql_insert1);
    $baza->selectDB($sql_insert2);
    $korisnik = $baza->selectDB($sql_upit_korisnik);
    while ($row = $korisnik->fetch_assoc()) {
        $korisnikID = $row["idkorisnik"];
    }
    $zahtjev = $baza->selectDB($sql_upit_zahtjev);
    while ($red = $zahtjev->fetch_assoc()) {
        $zahtjevID = $red["idzahtjev"];
    }
    $stranica = $baza->selectDB($sql_upit_stranica);
    while($redak = $stranica->fetch_assoc()) {
        $stranicaID = $redak["idstranica"];
    }
    $sql_insert3 = "INSERT INTO `slanje_zahtjeva`(`idkorisnik`, `idzahtjev`,`idstranica`) VALUES('" . $korisnikID . "', '" . $zahtjevID . "', '" . $stranicaID . "')";
    $baza->selectDB($sql_insert3);

    header("Location: index.php?odjava=0");
}


$baza->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="30.05.2018." />
        <meta name="autor" content="Valentino Poljak" />
        <link rel="stylesheet" type="text/css" href="../css/vpoljak_main.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/vpoljak.js"></script>
        <title>Kreiranje zahtjeva</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="oglasi.php">Blokiranje oglasa</a>
                    </li>
                    <li>
                        <a href="vrsteOglasa.php" class="active">Vrste oglasa</a>
                    </li>
                    <li>
                        <a href="zahtjevi.php">Vaši zahtjevi</a>
                    </li>
                    <li>
                        <a href="vasiOglasi.php">Vaši oglasi</a>
                    </li>
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="zahtjev" name="zahtjev" method="POST" action="#file" enctype="multipart/form-data">
                    <input id="sifraVrste" name="sifraVrste" type="hidden" value="<?php echo$idVrsteOglasa ?>"/>
                    <input id="naziv" name="naziv" type="text" placeholder="Naziv oglasa" />
                    <input id="opis" name="opis" type="text" placeholder="Opis oglasa"/>
                    <input id="url" name="url" type="text" placeholder="Url stranice"/>
                    <input id="datum" name="datum" type="date"/>
                    <label for="file">Slika oglasa: </label>
                    <input id="file" name="file" type="file"/>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>