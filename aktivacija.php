<?php

include("baza.class.php");
include("sesija.class.php");

if (Sesija::dajKorisnika() != NULL) {
    header("Location: index.php?odjava=0");
}

$baza = new Baza();
$baza->spojiDB();


$korisnik = "";
$aktivacijskiKod = "";
$zastavica = 0;

if (isset($_GET["korisnik"]) && isset($_GET["aktikod"])) {
    $korisnik = $_GET["korisnik"];
    $aktivacijskiKod = $_GET["aktikod"];
    $zastavica = 1;
}

var_dump($korisnik);
var_dump($aktivacijskiKod);
$sql_upit = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnik . "' AND kriptirana_lozinka LIKE '" . $aktivacijskiKod . "'";
$sql_update = "UPDATE korisnik SET status = '1' WHERE korisnicko_ime LIKE '" . $korisnik . "' AND kriptirana_lozinka LIKE '" . $aktivacijskiKod . "'";



$rezultat = $baza->selectDB($sql_upit);
if (mysqli_num_rows($rezultat) > 0) {
    $rezultat2 = $baza->selectDB($sql_update);
    header("Location: prijava.php");
}


$baza->zatvoriDB();
