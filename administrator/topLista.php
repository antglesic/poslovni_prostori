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



$head = "<thead>" . "<tr>" . "<th>Ime i prezime</th>" . "<th>Ukupno plaćeno</th>" . "</tr>" . "</thead>";
$table = "";

$sql_upit_placeno = "SELECT vrsta_oglasa.cijena*COUNT(oglas.korisnik_idkorisnik), korisnik.ime, korisnik.prezime FROM vrsta_oglasa, oglas, korisnik WHERE oglas.korisnik_idkorisnik LIKE korisnik.idkorisnik AND oglas.vrsta_oglasa_idvrsta_oglasa LIKE vrsta_oglasa.idvrsta_oglasa GROUP BY oglas.korisnik_idkorisnik ORDER BY 1 DESC";
$statistika1 = $baza->selectDB($sql_upit_placeno);

while ($row = mysqli_fetch_array($statistika1)) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row[0] . "</td>";
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
        <title>Statistika plaćenih oglasa</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="novaLokacija.php">Nova lokacija</a>
                    </li>
                    <li>
                        <a href="statistikaKlikova.php">Statistika klikova</a>
                    </li>
                    <li>
                        <a href="statistikaPlacenih.php">Statistika plaćenih</a>
                    </li>
                    <li>
                        <a href="topLista.php">Top lista</a>
                    </li>
                    <li>
                        <a href="indexAdmin.php">Admin</a>
                    </li>
                    <li>
                        <a href="korisnickiPogled/index.php?odjava=0">User</a>
                    </li>
                    <li>
                        <a href="moderatorPogled/index.php?odjava=0">Mod</a>
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