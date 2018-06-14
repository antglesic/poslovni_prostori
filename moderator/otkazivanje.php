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

$noviDatum = "";
$stariDatum = "";
$idPrijave = "";
$emailKorisnika = "";
$zastavica = 0;


if (isset($_GET["idPrijave"]) && isset($_GET["datum"])) {
    $idPrijave = $_GET["idPrijave"];
    $noviDatum = $_GET["datum"];
    $zastavica = 1;
}

if ($zastavica === 1) {
    $sql_upit_prijava = "SELECT * FROM prijavljena_dvorana WHERE idprijave LIKE '" . $idPrijave . "'";
    $prijava = $baza->selectDB($sql_upit_prijava);
    while ($row = $prijava->fetch_assoc()) {
        $emailKorisnika = $row["email"];
        $stariDatum = $row["datum"];
    }
    $sql_update_termina = "UPDATE prijavljena_dvorana SET datum = '" . $noviDatum . "' WHERE idprijave LIKE '" . $idPrijave . "'";
    $rez1 = $baza->selectDB($sql_update_termina);
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('otkazan termin', '" . $trenutno . "', 'termin " . $idPrijave . " premješten na " . $noviDatum . "')";
    $rez2 = $baza->selectDB($sql_insert_dnevnik);

    $mail_to = $emailKorisnika;   //$_POST["email"];
    $mail_from = "From: WebDiP_2017@foi.hr";
    $mail_subject = "Otkazan termin";      //$_POST["subjekt"];
    $mail_body = "Vaš termin se premješta sa " . $stariDatum . " na " . $noviDatum;

    if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
        //echo 'Uspjeh!';
    } else {
        //echo 'Neuspjeh!';
    }
    header("Location: termini.php");
}


$baza->zatvoriDB();
