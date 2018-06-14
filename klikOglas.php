<?php
include("baza.class.php");
include("sesija.class.php");

if (Sesija::dajKorisnika() != NULL) {
    header("Location: index.php?odjava=0");
}

$idOglasa = "";
$idStranice = "";
$brojKlikova = "";
$url = "";
$zastavica = 0;

if (isset($_GET["idOglasa"])) {
    $idOglasa = $_GET["idOglasa"];
}

$baza = new Baza();
$baza->spojiDB();

$sql_upit_oglas = "SELECT * FROM oglas WHERE idoglas LIKE '" . $idOglasa . "'";

$oglas = $baza->selectDB($sql_upit_oglas);
while ($row = $oglas->fetch_assoc()) {
    $brojKlikova = $row["broj_klikova"];
    $idStranice = $row["stranica_idstranica"];
}
$brojKlikova++;
$sql_update_klikOglas = "UPDATE oglas SET broj_klikova = '" . $brojKlikova . "' WHERE idoglas LIKE '" . $idOglasa . "'";
$rez1 = $baza->selectDB($sql_update_klikOglas);

$sql_upit_stranica = "SELECT * FROM stranica WHERE idstranica LIKE '" . $idStranice . "'";
$rez2 = $baza->selectDB($sql_upit_stranica);
while ($redak = $rez2->fetch_assoc()) {
    $url = $redak["url_stranice"];
    $zastavica = 1;
}
header("Location: oglasi.php");

$baza->zatvoriDB();
?>

