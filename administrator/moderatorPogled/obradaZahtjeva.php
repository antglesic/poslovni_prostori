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

$idZahtjeva = "";
$operacija = "";

if(isset($_GET["idZahtjeva"]) && isset($_GET["operacija"])) {
    $idZahtjeva = $_GET["idZahtjeva"];
    $operacija = $_GET["operacija"];
}

if($operacija == '0') {
    $sql_update_odbijeno = "UPDATE zahtjev SET status = '2' WHERE idzahtjev LIKE '" . $idZahtjeva . "'";
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('odbijen zahtjev', '" . $trenutno . "', 'Zahtjev " . $idZahtjeva . " odbijen od " . $_SESSION["korisnik"] . "')";
    $rez1 = $baza->selectDB($sql_update_odbijeno);
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: zahtjevi.php");
}
if($operacija == '1') {
    $sql_update_prihvaceno = "UPDATE zahtjev SET status = '1' WHERE idzahtjev LIKE '" . $idZahtjeva . "'";
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('prihvacen zahtjev', '" . $trenutno . "', 'Zahtjev " . $idZahtjeva . " prihvacen od " . $_SESSION["korisnik"] . "')";
    $rez1 = $baza->selectDB($sql_update_prihvaceno);
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: zahtjevi.php");
}


$baza->zatvoriDB();