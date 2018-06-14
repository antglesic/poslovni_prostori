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
$idOglasa = "";
$idKorisnik = "";
$mailKorisnika = "";

if (isset($_GET["idZahtjeva"]) && isset($_GET["operacija"])) {
    $idZahtjeva = $_GET["idZahtjeva"];
    $operacija = $_GET["operacija"];
}

if ($operacija == '0') {
    $sql_update_odbijeno = "UPDATE zahtjev SET status = '2' WHERE idzahtjev LIKE '" . $idZahtjeva . "'";
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('odbijen zahtjev', '" . $trenutno . "', 'Zahtjev " . $idZahtjeva . " odbijen od " . $_SESSION["korisnik"] . "')";
    $rez1 = $baza->selectDB($sql_update_odbijeno);
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: blokiranjeZahtjeva.php");
}
if ($operacija == '1') {
    $sql_upit_oglasid = "SELECT * FROM zahtjev WHERE idzahtjev LIKE '" . $idZahtjeva . "'";
    $oglasi = $baza->selectDB($sql_upit_oglasid);
    while ($row = $oglasi->fetch_assoc()) {
        $idOglasa = $row["idoglas"];
    }
    $sql_upit_korisnikid = "SELECT * FROM oglas WHERE idoglas LIKE '" . $idOglasa . "'";
    $oglas = $baza->selectDB($sql_upit_korisnikid);
    while ($row = $oglas->fetch_assoc()) {
        $idKorisnik = $row["korisnik_idkorisnik"];
    }
    $sql_upit_korisnik = "SELECT * FROM korisnik WHERE idkorisnik LIKE '" . $idKorisnik . "'";
    $korisnik = $baza->selectDB($sql_upit_korisnik);
    while ($row = $korisnik->fetch_assoc()) {
        $emailKorisnika = $row["email"];
    }
    $sql_update_prihvaceno = "UPDATE zahtjev SET status = '1' WHERE idzahtjev LIKE '" . $idZahtjeva . "'";
    $sql_update_oglas = "UPDATE oglas SET status_oglasa = 'Neaktivan' WHERE idoglas LIKE '" . $idOglasa . "'";
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('prihvacen zahtjev', '" . $trenutno . "', 'Zahtjev " . $idZahtjeva . " prihvacen od " . $_SESSION["korisnik"] . "')";
    $rez1 = $baza->selectDB($sql_update_prihvaceno);
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    $rez3 = $baza->selectDB($sql_update_oglas);

    $mail_to = $emailKorisnika;   //$_POST["email"];
    $mail_from = "From: WebDiP_2017@foi.hr";
    $mail_subject = "Blokiranje oglasa";      //$_POST["subjekt"];
    $mail_body = "Vaš oglas " . $idOglasa . " je blokiran!";

    if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
        $povratna_informacija1 = "Uspjesno ste registrirali vas racun!" . "<br>";
    } else {
        $povratna_informacija1 = "Problem kod registracije korisnickog racuna!" . "<br>";
    }

    header("Location: blokiranjeZahtjeva.php");
}


$baza->zatvoriDB();

