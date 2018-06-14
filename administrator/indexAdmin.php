<?php 
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if(isset($_SESSION["korisnik"])) {
    if($_SESSION["uloga"] === 'mod') {
        header("Location: ../moderator/index.php");
    }
    if($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../korisnik/index.php");
    }
}
$baza = new Baza();
$baza->spojiDB();
$odjava = 0;

if(isset($_GET["odjava"])) {
    $odjava = $_GET["odjava"];
}

if($odjava == 1) {
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('odjava korisnika', '" . $trenutno . "', 'odjavljen korisnik " . $_SESSION["korisnik"] . "')";
    $rez = $baza->selectDB($sql_insert_dnevnik);
    Sesija::obrisiSesiju();
    header("Location: ../index.php?odjava=0");
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
        <title>Naslovnica</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="korisnici.php">Upravljanje računima</a>
                    </li>
                    <li>
                        <a href="dnevnik.php">Dnevnik</a>
                    </li>
                    <li>
                        <a href="moderatori.php">Moderatori</a>
                    </li>
                    <li>
                        <a href="teme.php">Tema</a>
                    </li>
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>
    </body>
</html>