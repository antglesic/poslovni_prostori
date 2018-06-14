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

$sql_upit_lokacije = "SELECT * FROM oglas WHERE status_oglasa LIKE 'Aktivan'";
$sql_upit_stranice = "SELECT * FROM stranica";
$lokacije = $baza->selectDB($sql_upit_lokacije);
$stranice = $baza->selectDB($sql_upit_stranice);

$head = "<thead>" . "<tr>" . "<th>Ime oglasa</th>" . "<th>Statistika</th>" . "<th>Broj klikova</th>" . "<th>Stranica</th>" . "</tr>" . "</thead>";
$table = "";
while ($row = $lokacije->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["naziv_oglasa"] . "</td>" . "<td>" . $row["statistika"] . "</td>" . "<td>" . $row["broj_klikova"] . "</td>" . "<td>" . "<a href='blokiranjeOglasa.php?idOglas=" . $row["idoglas"] . "'>Blokiraj</a>" . "</td>";
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
        

        <script type="text/javascript" src="../../js/vpoljak.js"></script>
        <link rel="stylesheet" type="text/css" href="../../css/vpoljak_main.css">
        <title>Blokiranje oglasa</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="../index.php?odjava=0">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="oglasi.php" class="active">Blokiranje oglasa</a>
                    </li>
                    <li>
                        <a href="vrsteOglasa.php">Vrste oglasa</a>
                    </li>
                    <li>
                        <a href="zahtjevi.php">Vaši zahtjevi</a>
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