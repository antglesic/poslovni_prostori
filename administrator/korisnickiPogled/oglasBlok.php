<?php
include("../../baza.class.php");
include("../../sesija.class.php");

Sesija::kreirajSesiju();

if(isset($_SESSION["korisnik"])) {
    if($_SESSION["uloga"] === 'mod') {
        header("Location: ../../moderator/index.php");
    }
    if($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../../korisnik/index.php");
    }
}

$baza = new Baza();
$baza->spojiDB();

$oglas = "";
$razlog = "";
$zastavica = 0;

if(isset($_GET["oglas"]) && isset($_GET["razlog"])) {
    $oglas = $_GET["oglas"];
    $razlog = $_GET["razlog"];
    $zastavica = 1;
}

if($zastavica === 1) {
    $sql_update_oglas = "UPDATE oglas SET status_oglasa = 'Neaktivan' WHERE idoglas LIKE '" . $oglas . "'";
    $trenutno = date("Y-m-d H:i:s");
    $naslovZahtjeva = "Zahtjev za blokiranjem oglasa";
    $sql_insert_zahtjev = "INSERT INTO `zahtjev`(`datum_i_vrijeme`, `naslov_zahtjeva`, `opsi_zahtjeva`) VALUES('" . $trenutno . "', '" . $naslovZahtjeva . "', '" . $razlog . "')";
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('blokiranje oglasa', '" . $trenutno . "', 'blokiran oglas " . $oglas . "')";
    $rez1 = $baza->selectDB($sql_update_oglas);
    $rez2 = $baza->selectDB($sql_insert_zahtjev);
    $rez3 = $baza->selectDB($sql_insert_dnevnik);
    ("Location: index.php?odjava=0");
}

$baza->zatvoriDB();