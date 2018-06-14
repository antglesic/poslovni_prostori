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

$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $baza->selectDB($sql_upit_korisnik);

$korisnikID = "";
$korisnikEmail = "";
while ($row = $korisnik->fetch_assoc()) {
    $korisnikID = $row["idkorisnik"];
    $korisnikEmail = $row["email"];
}

$sql_upit_dvorane = "SELECT dvorana.iddvorana, dvorana.ime_dvorane FROM dvorana, lokacija WHERE lokacija.idmoderatora LIKE '" . $korisnikID . "' AND dvorana.idlokacija LIKE lokacija.idlokacija";
$dvorane = $baza->selectDB($sql_upit_dvorane);

$selected = "<select id='dvorana' name='dvorana'>";
while ($row = mysqli_fetch_array($dvorane)) {
    $prikaz = $row['ime_dvorane'];
    $selected .= "<option value='" . $row['iddvorana'] . "'>" . $prikaz . "</option>";
}
$selected .= "</select>";

$zastavica = 0;
$datumPrijave = "";
$datumKoristenja = "";
$daniTrajanja = "";
$dvoranaID = "";

if (isset($_POST["submit"])) {
    $datumPrijave = $_POST["datPrijave"];
    $datumKoristenja = $_POST["datKoristenja"];
    $daniTrajanja = $_POST["brojDana"];
    $dvoranaID = $_POST["dvorana"];
    $zastavica = 1;
}

$kontrolnaZastavica = 0;

if ($zastavica === 1) {
    $sql_upit_prijave = "SELECT * FROM prijavljena_dvorana";
    $prijave = $baza->selectDB($sql_upit_prijave);
    $datumPostojecihPrijava = "";
    $iddvoranePostojecePrijave = "";
    while ($row = $prijave->fetch_assoc()) {
        $datumPostojecihPrijava = $row["datum"];
        $iddvoranePostojecePrijave = $row["iddvorana"];
        if ($dvoranaID == $iddvoranePostojecePrijave) {
            if ((strpos($datumKoristenja, $datumPostojecihPrijava) !== false)) {
                $kontrolnaZastavica = 1;
            }
        }
    }
    if ($kontrolnaZastavica === 0) {
        $sol = sha1(time());
        $sigurnosniKod = sha1($sol . "--" . $korisnikEmail);
        $sql_insert_termin = "INSERT INTO `prijavljena_dvorana`(`iddvorana`, `trajanje`, `email`, `datum`, `sigurnosniKod`) VALUES('" . $dvoranaID . "', '" . $daniTrajanja . "', '" . $korisnikEmail . "', '" . $datumKoristenja . "', '" . $sigurnosniKod . "')";
        $rez = $baza->selectDB($sql_insert_termin);
        $trenutno = date("Y-m-d H:i:s");
        $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('definiran novi termin', '" . $trenutno . "', 'definiran termin za dvoranu ID=" . $dvoranaID . "')";
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
        <!-- jQuery lib -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- datatable lib -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
        <script type='text/javascript' charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script type='text/javascript' charset="utf8" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
        <script type='text/javascript' charset="utf8" src='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.js'></script>
        <link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.css">
        <link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css'>


        <script type="text/javascript" src="../js/vpoljak.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/vpoljak_main.css">
        <title>Kreiranje termina</title>
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
                <form class="login-form" id="dvorana" name="dvorana" method="POST" action="<?php echo$_SERVER["PHP_SELF"]; ?>">
                    <label for="datPrijave">Rok prijave: </label>
                    <input id="datPrijave" name="datPrijave" type="date"/>
                    <label for="datKoristenja">Datum korištenja: </label>
                    <input id="datKoristenja" name="datKoristenja" type="date"/>
                    <input id="brojDana" name="brojDana" type="text" placeholder="Trajanje (dani)"/>
                    <p id="dvorane">
                        <?php
                        echo $selected;
                        ?>
                    </p><br>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>