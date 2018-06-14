<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if(isset($_SESSION["korisnik"])) {
    if($_SESSION["uloga"] === 'admin') {
        header("Location: ../administrator/index.php");
    }
    if($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../korisnik/index.php");
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

$idkorisnik = "";
$nazivOglasa = "";
$idVrsteOglasa = "";
$url = "";
$slika = "";
$idstranice = "";


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
    $sql_zahtjev = "SELECT zahtjev.naslov_zahtjeva, zahtjev.idvrste_oglasa, stranica.url_stranice, stranica.slika, slanje_zahtjeva.idstranica, slanje_zahtjeva.idkorisnik FROM zahtjev, slanje_zahtjeva, stranica, vrsta_oglasa WHERE zahtjev.idzahtjev LIKE '" . $idZahtjeva . "' AND slanje_zahtjeva.idzahtjev LIKE zahtjev.idzahtjev AND zahtjev.idvrste_oglasa LIKE vrsta_oglasa.idvrsta_oglasa AND stranica.idstranica LIKE slanje_zahtjeva.idstranica";
    $podaci = $baza->selectDB($sql_zahtjev);
    while($red = $podaci->fetch_assoc()) {
        $nazivOglasa = $red["naslov_zahtjeva"];
        $idVrsteOglasa = $red["idvrste_oglasa"];
        $url = $red["url_stranice"];
        $slika = $red["slika"];
        $idkorisnik = $red["idkorisnik"];
        $idstranice = $red["idstranica"];
    }
    $sql_novi_oglas = "INSERT INTO `oglas`(`sirina_oglasa`, `visina_oglasa`, `naziv_oglasa`, `statistika`, `status_oglasa`, `stranica_idstranica`, `zahtjev_idzahtjev`, `vrsta_oglasa_idvrsta_oglasa`, `korisnik_idkorisnik`, `broj_klikova`, `slika`, `link`) VALUES('250', '325', '" . $nazivOglasa . "', '250', 'Aktivan', '" . $idstranice . "', '" . $idZahtjeva . "', '" . $idVrsteOglasa . "', '" . $idkorisnik . "', '0', '" . $slika . "', '" . $url . "')";
    $noviOglas = $baza->selectDB($sql_novi_oglas);
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('prihvacen zahtjev', '" . $trenutno . "', 'Zahtjev " . $idZahtjeva . " prihvacen od " . $_SESSION["korisnik"] . "')";
    $rez1 = $baza->selectDB($sql_update_prihvaceno);
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: zahtjevi.php");
}


$baza->zatvoriDB();