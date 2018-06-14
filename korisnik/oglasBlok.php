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

$baza = new Baza();
$baza->spojiDB();

$oglas = "";
$razlog = "";
$idvrste = "";
$idstranice = "";
$zastavica = 0;

if (isset($_GET["oglas"]) && isset($_GET["razlog"])) {
    $oglas = $_GET["oglas"];
    $razlog = $_GET["razlog"];
}

$sql_upit_vrsta = "SELECT * FROM oglas WHERE idoglas LIKE '" . $oglas . "'";
$rezultat = $baza->selectDB($sql_upit_vrsta);
while($row = $rezultat->fetch_assoc()) {
    $idvrste = $row["vrsta_oglasa_idvrsta_oglasa"];
    $idstranice = $row["stranica_idstranica"];
}

$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $baza->selectDB($sql_upit_korisnik);

$idZahtjeva = "";
$idKorisnika = "";

while($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["idkorisnik"];
}

$trenutno = date("Y-m-d H:i:s");
$naslovZahtjeva = "Zahtjev za blokiranjem oglasa";
$sql_insert_zahtjev = "INSERT INTO `zahtjev`(`datum_i_vrijeme`, `naslov_zahtjeva`, `opsi_zahtjeva`, `idoglas`, `idvrste_oglasa`, `status`) VALUES('" . $trenutno . "', '" . $naslovZahtjeva . "', '" . $razlog . "', '" . $oglas . "', '" . $idvrste . "', '0')";
$sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('blokiranje oglasa', '" . $trenutno . "', 'blokiran oglas " . $oglas . "')";
$rez2 = $baza->selectDB($sql_insert_zahtjev);
$sql_upit_zahtjev = "SELECT * FROM zahtjev WHERE opsi_zahtjeva LIKE '" . $razlog . "'";
$zahtjev = $baza->selectDB($sql_upit_zahtjev);
while($row = $zahtjev->fetch_assoc()) {
    $idZahtjeva = $row["idzahtjev"];
}
$sql_insert_slanje = "INSERT INTO `slanje_zahtjeva`(`idkorisnik`, `idzahtjev`, `idstranica`) VALUES('". $idKorisnika ."', '" . $idZahtjeva . "', '" . $idstranice . "')";
$rez1 = $baza->selectDB($sql_insert_slanje);

$rez3 = $baza->selectDB($sql_insert_dnevnik);
header("Location: index.php?odjava=0");


$baza->zatvoriDB();
