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

$idPrijave = "";
$zastavica = 0;

if (isset($_GET["idPrijave"])) {
    $idPrijave = $_GET["idPrijave"];
    $zastavica = 1;
}

$stariDatum = "";
$emailKorisnika = "";


$sql_upit_termini = "SELECT * FROM prijavljena_dvorana WHERE idprijave LIKE '" . $idPrijave . "'";
$termin = $baza->selectDB($sql_upit_termini);
while ($row = $termin->fetch_assoc()) {
    $stariDatum = $row["datum"];
    $emailKorisnika = $row["email"];
}
if (isset($_POST["submit"])) {
    header("Location: otkazivanje.php?idPrijave=" . $_POST["sifraPrijave"] . "&datum=" . $_POST["noviDatum"]);
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
        <title>Otkazivanje termina</title>
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
                <form class="login-form" id="novi" name="novi" method="POST" action="<?php echo$_SERVER["PHP_SELF"]; ?>">
                    <input id="sifraPrijave" name="sifraPrijave" type="hidden" value="<?php echo$idPrijave; ?>"/>
                    <h3>Stari datum: <?php echo$stariDatum; ?></h3><br>
                    <label for="noviDatum">Novi datum termina: </label>
                    <input id="noviDatum" name="noviDatum" type="date"/>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>