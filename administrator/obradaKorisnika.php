<?php

include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] === 'mod') {
        header("Location: ../moderator/index.php");
    }
    if ($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../korisnik/index.php");
    }
}
$baza = new Baza();
$baza->spojiDB();

$idKorisnika = "";
$operacija = "";

if (isset($_GET["idKorisnika"]) && isset($_GET["operacija"])) {
    $idKorisnika = $_GET["idKorisnika"];
    $operacija = $_GET["operacija"];
}

if ($operacija == '1') {
    $sql_update_otkljucavanje = "UPDATE korisnik SET status = '1', broj_gresaka = '0' WHERE idkorisnik LIKE '" . $idKorisnika . "'";
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('otključan korisnik', '" . $trenutno . "', 'Otključan korisnički račun " . $idKorisnika . "')";
    $rez1 = $baza->selectDB($sql_update_otkljucavanje);
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: korisnici.php");
}
if ($operacija == '2') {
    $sql_update_otkljucavanje = "UPDATE korisnik SET status = '2' WHERE idkorisnik LIKE '" . $idKorisnika . "'";
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('zaključan korisnik', '" . $trenutno . "', 'Zaključan korisnički račun " . $idKorisnika . "')";
    $rez1 = $baza->selectDB($sql_update_otkljucavanje);
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: korisnici.php");
}


$baza->zatvoriDB();
