<?php
include("../../baza.class.php");
include("../../sesija.class.php");

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] === 'admin') {
        header("Location: ../../administrator/index.php");
    }
    if ($_SESSION["uloga"] === 'mod') {
        header("Location: ../../moderator/index.php");
    }
}

$baza = new Baza();
$baza->spojiDB();

$email = "";
$sifraDvorane = "";

if(isset($_GET["dvorana"]) && isset($_GET["email"])) {
    $email = $_GET["email"];
    $sifraDvorane = $_GET["dvorana"];
}

$sql_update_dvorane = "UPDATE dvorana SET zauzeto = '1' WHERE iddvorana LIKE '" . $sifraDvorane . "'";

$trenutno = date("Y-m-d");
$opis = "Rezervirana dvorana " . $sifraDvorane;
$sol = sha1(time());
$sigurnosniKod = sha1($sol . "--" . $email);

$sql_insert_prijavaDvorane = "INSERT INTO `prijavljena_dvorana`(`iddvorana`, `email`, `datum`, `sigurnosniKod`) VALUES('" . $sifraDvorane . "', '" . $email . "', '" . $trenutno . "', '" . $sigurnosniKod . "')";
$sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('rezervacija dvorane', '" . $trenutno . "', '" . $opis . "')";

$mail_to = $email;   //$_POST["email"];
$mail_from = "From: WebDiP_2017@foi.hr";
$mail_subject = "Sigurnosni kod za povratnu informaciju";      //$_POST["subjekt"];
$link = "http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x119/povratnaInformacija.php?kod=" . $sigurnosniKod;
$mail_body = "Vaš sigurnosni kod glasi ovako: " . $sigurnosniKod;
$baza->selectDB($sql_update_dvorane);
$baza->selectDB($sql_insert_prijavaDvorane);
$baza->selectDB($sql_insert_dnevnik);

if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
    header("Location: ../index.php?odjava=0");
} else {
    $povratna_informacija1 = "Problem kod rezervacije dvorane!" . "<br>";
}


$baza->zatvoriDB();