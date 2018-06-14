<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if(isset($_SESSION["korisnik"])) {
    if($_SESSION["uloga"] === 'admin') {
        header("Location: ../administrator/index.php");
    }
    if($_SESSION["uloga"] === 'mod') {
        header("Location: ../moderator/index.php");
    }
}

$baza = new Baza();
$baza->spojiDB();

$korisnikID = "";
$statusZahtjeva = "";
$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $baza->selectDB($sql_upit_korisnik);
while($row = $korisnik->fetch_assoc()) {
    $korisnikID = $row["idkorisnik"];
}

$sql_upit_zahtjevi = "SELECT zahtjev.naslov_zahtjeva, zahtjev.opsi_zahtjeva, zahtjev.idvrste_oglasa, stranica.url_stranice, stranica.slika, zahtjev.status, vrsta_oglasa.vrsta_oglasa FROM zahtjev, stranica, slanje_zahtjeva, oglas, vrsta_oglasa WHERE zahtjev.idzahtjev LIKE slanje_zahtjeva.idzahtjev AND slanje_zahtjeva.idkorisnik LIKE '15' AND vrsta_oglasa.idvrsta_oglasa LIKE zahtjev.idvrste_oglasa AND stranica.idstranica LIKE slanje_zahtjeva.idstranica GROUP BY zahtjev.idzahtjev" ;
$zahtjevi = $baza->selectDB($sql_upit_zahtjevi);

$head = "<thead>" . "<tr>" . "<th>Naslov zahtjeva</th>" . "<th>Opis zahtjeva</th>" . "<th>Vrsta oglasa</th>" . "<th>URL stranice</th>" . "<th>Slika</th>" . "<th>Status</th>" . "</tr>" . "</thead>";
$table = "";
while ($row = $zahtjevi->fetch_assoc()) {
    if($row["status"] == '0') {
        $statusZahtjeva = "U čekanju";
    }
    if($row["status"] == '1') {
        $statusZahtjeva = "Odobren";
    }
    if($row["status"] == '2') {
        $statusZahtjeva = "Odbijen";
    }
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["naslov_zahtjeva"] . "</td>" . "<td>" . $row["opsi_zahtjeva"] . "</td>" . "<td>" . $row["vrsta_oglasa"] . "</td>" . "<td><a target='_blank' href=" . $row["url_stranice"] . ">" . $row["url_stranice"] ."</a></td>" . "<td>" . "<img src='../slike/" . $row["slika"] . "' width=" . 50 . " height=" . 75 ."/> </td>" . "<td>" . $statusZahtjeva . "</td>";
    $table = $table . "</tr>";
}

$baza->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="30.05.2018." />
        <meta name="autor" content="Valentino Poljak" />
        <!-- jQuery lib -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- datatable lib -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
        <script type='text/javascript' charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script type='text/javascript' charset="utf8" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
        <script type='text/javascript' charset="utf8" src='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.js'></script>
        <link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.css">
        <link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css'>
        

        <script type="text/javascript" src="../js/vpoljak.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/vpoljak_main.css">
        <title>Vaši zahtjevi</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="oglasi.php">Blokiranje oglasa</a>
                    </li>
                    <li>
                        <a href="vrsteOglasa.php">Vrste oglasa</a>
                    </li>
                    <li>
                        <a href="zahtjevi.php" class="active">Vaši zahtjevi</a>
                    </li>
                    <li>
                        <a href="vasiOglasi.php">Vaši oglasi</a>
                    </li>
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

        <table id="tablica" class="display" class="ui celled table" style="width:90%">
            <?php
            echo $head;
            ?>
            <tbody>
                <?php
                echo $table;
                ?>
            </tbody>
        </table>

    </body>
</html>