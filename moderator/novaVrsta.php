<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] === 'admin') {
        header("Location: ../administrator/index.php");
    }
    if ($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../korisnik/index.php");
    }
}
$baza = new Baza();
$baza->spojiDB();


$zastavica = 0;
$nazivVrste = "";
$datum = "";
$vrijeme = "";
$cijena = "";

if (isset($_POST["submit"])) {
    $nazivVrste = $_POST["naziv_vrste"];
    $datum = $_POST["datum"];
    $vrijeme = $_POST["vrijeme"];
    $cijena = $_POST["cijena"];
    $zastavica = 1;
}

if ($zastavica === 1) {
    $sql_upit_vrsta = "SELECT * FROM vrsta_oglasa";
    $vrste = $baza->selectDB($sql_upit_vrsta);
    $brojac = 0;
    while($row = $vrste->fetch_assoc()) {
        if($row["vrsta_oglasa"] == $nazivVrste) {
            $brojac++;
        }
    }
    if($brojac != 0) {
        header("Location: index.php?odjava=0");
    } else {
        $datumVrijeme = $datum . " " . $vrijeme . ":00";
        $sql_insert_dvorana = "INSERT INTO `vrsta_oglasa`(`vrsta_oglasa`, `trajanje_oglasa`, `cijena`) VALUES ('" . $nazivVrste ."','" . $datumVrijeme . "', '" . $cijena ."')";
        $rez = $baza->selectDB($sql_insert_dvorana);
        $trenutno = date("Y-m-d H:i:s");
        $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('kreirana nova vrsta oglasa', '" . $trenutno . "', 'Kreirana nova vrsta oglasa: " . $nazivVrste . "')";
        $rez2 = $baza->selectDB($sql_insert_dnevnik);
        header("Location: index.php?odjava=0");
    }
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
        <title>Kreiranje nove vrste oglasa</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="novaDvorana.php">Nova dvorane</a>
                    </li>
                    <li>
                        <a href="termini.php">Termini</a>
                    </li>
                    <li>
                        <a href="zahtjevi.php">Zahtjevi za oglas</a>
                    </li>
                    <li>
                        <a href="zahtjeviBlok.php">Zahtjevi za blok</a>
                    </li>
                    <li>
                        <a href="novaVrsta.php">Nova vrsta oglasa</a>
                    </li>
                    <li>
                        <a href="korisnickiPogled/index.php?odjava=0">Korisnički pogled</a>
                    </li>
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="vrsta_oglasa" name="vrsta_oglasa" method="POST" action="<?php echo$_SERVER["PHP_SELF"]; ?>">
                    <input id="naziv_vrste" name="naziv_vrste" type="text" placeholder="Naziv vrste oglasa" />
                    <label for="datum">Datum i vrijeme trajanja: </label>
                    <input id="datum" name="datum" type="date"/>
                    <input id="vrijeme" name="vrijeme" type="time"/>
                    <input id="cijena" name="cijena" type="text" placeholder="Cijena"/>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>