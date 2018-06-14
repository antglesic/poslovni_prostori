<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] === 'mod') {
        header("Location: ../moderator/index.php");
    }
    if ($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../korisnik/index.php");
    }
}
$baza = new Baza();
$baza->spojiDB();

$sql_upit_korisnici = "SELECT korisnik.idkorisnik, korisnik.ime, korisnik.prezime, korisnik.iduloga, uloga.tip_uloge FROM korisnik, uloga WHERE korisnik.iduloga LIKE uloga.iduloga AND korisnik.korisnicko_ime NOT LIKE '" . $_SESSION["korisnik"] . "'";
$korisnici = $baza->selectDB($sql_upit_korisnici);

$head = "<thead>" . "<tr>" . "<th>Ime i prezime</th>" . "<th>Uloga</th>" . "<th>Dodaj prava</th>" . "<th>Oduzmi prava</th>" . "</tr>" . "</thead>";
$table = "";


while ($row = $korisnici->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["tip_uloge"] . "</td>" . "<td><a href='obradaModeratora.php?idKorisnika=" . $row["idkorisnik"] . "&operacija=1'>DAJ PRAVA MODERATORA</a></td>" . "<td><a href='obradaModeratora.php?idKorisnika=" . $row["idkorisnik"] . "&operacija=2'>ODUZMI PRAVO MODERATORA</a></td>";
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
        <title>Moderatori</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="korisnici.php">Upravljanje računima</a>
                    </li>
                    <li>
                        <a href="dnevnik.php">Dnevnik</a>
                    </li>
                    <li>
                        <a href="moderatori.php">Moderatori</a>
                    </li>
                    <li>
                        <a href="teme.php">Tema</a>
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