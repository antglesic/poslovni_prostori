<?php
include("baza.class.php");

$baza = new Baza();
$baza->spojiDB();

$sql_upit = "SELECT * FROM oglas WHERE status_oglasa LIKE 'Aktivan'";
$rez = $baza->selectDB($sql_upit);

$oglasi = array();
while($row = $rez->fetch_assoc()) {
    array_push($oglasi, $row);
}

header('Content-Type: application:/json');
echo json_encode($oglasi);

?>
