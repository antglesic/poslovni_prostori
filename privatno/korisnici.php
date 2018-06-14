<?php
include("../baza.class.php");

$baza = new Baza();
$baza->spojiDB();

$sql_upit_korisnici = "SELECT * FROM korisnik";
$korisnici = $baza->selectDB($sql_upit_korisnici);

$head = "<thead>" . "<tr>" . "<th>Korisnicko ime</th>" . "<th>Ime</th>" . "<th>Prezime</th>" . "<th>Email</th>" . "<th>Lozinka</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $korisnici->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["korisnicko_ime"] . "</td>" . "<td>" . $row["ime"] . "</td>" . "<td>" . $row["prezime"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["lozinka"] . "</td>";
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
        <title>Korisnici</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="../index.php">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="../lokacije.php" class="active">Popis lokacija</a>
                    </li>
                    <li>
                        <a href="../https://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x119/prijava.php">Prijava</a>  
                    </li>
                    <li>
                        <a href="../registracija.php">Registracija</a>  
                    </li>
                    <li>
                        <a href="../oglasi.php">Oglasi</a>
                    </li>
                    <li class="last">
                        <a href="../povratnaInformacija.php">Povratna informacija</a>
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