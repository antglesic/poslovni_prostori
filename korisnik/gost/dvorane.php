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

$idLokacije = "";

if(isset($_GET["idLokacije"])) {
   $idLokacije = $_GET["idLokacije"]; 
}

$baza = new Baza();
$baza->spojiDB();

$sql_upit_dvorane = "SELECT * FROM dvorana WHERE idlokacija LIKE '" . $idLokacije . "' AND zauzeto LIKE '0'";
$dvorane = $baza->selectDB($sql_upit_dvorane);

$head = "<thead>" . "<tr>" . "<th>Ime Dvorane</th>" . "<th>Broj mjesta</th>" . "<th>Vrsta koristenja</th>" . "<th>Odabir</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $dvorane->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["ime_dvorane"] . "</td>" . "<td>" . $row["broj_mjesta"] . "</td>" . "<td>" . $row["vrsta_koristenja"] . "</td>" . "<td>" . "<a href=prijavaDvorane.php?idDvorane=" . $row["iddvorana"] . ">Prijavi</a>" . "</td>";
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
        <title>Dvorane</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="../index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="lokacije.php">Popis lokacija</a>
                    </li>
                    <li>
                        <a href="oglasi.php">Oglasi</a>
                    </li>
                    <li>
                        <a href="povratnaInformacija.php">Povratna informacija</a>
                    </li>
                    <li>
                        <a href="o_autoru.html">O autoru</a>
                    </li>
                    <li>
                        <a href="dokumentacija.html">Dokumentacija</a>
                    </li>

                    <li class="last" style="float:right">
                        <a href="../index.php?odjava=1">Odjava</a>
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