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


$sql_upit_zahtjevi = "SELECT zahtjev.idzahtjev, zahtjev.naslov_zahtjeva, zahtjev.opsi_zahtjeva, zahtjev.idvrste_oglasa, zahtjev.datum_i_vrijeme, vrsta_oglasa.idvrsta_oglasa, vrsta_oglasa.vrsta_oglasa FROM zahtjev, vrsta_oglasa, oglas WHERE zahtjev.idvrste_oglasa LIKE vrsta_oglasa.idvrsta_oglasa AND zahtjev.status LIKE '0' AND zahtjev.idoglas LIKE oglas.idoglas";
$zahtjevi = $baza->selectDB($sql_upit_zahtjevi);

$content = "<div class='timeline'>";
$brojac = 0;
while ($row = $zahtjevi->fetch_assoc()) {
    if ($brojac === 0) {
        $content = $content . "<div class='container left'>" . "<div class='content'>" . "<h2>" . $row["naslov_zahtjeva"] . "</h2>" . "<p>" . $row["opsi_zahtjeva"] . "</p>" . "<p>" . $row["vrsta_oglasa"] . "</p>" . "<p>" . $row["datum_i_vrijeme"] . "</p>" . "<p>" . "<a class='button1' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=1'>PRIHVATI</a></p><br>" . "<p>" . "<a class='button2' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=0'>ODBIJ</a></p><br>" . "</div>" . "</div>";
    } else {
        if ($brojac === 1) {
            $content = $content . "<div class='container right'>" . "<div class='content'>" . "<h2>" . $row["naslov_zahtjeva"] . "</h2>" . "<p>" . $row["opsi_zahtjeva"] . "</p>" . "<p>" . $row["vrsta_oglasa"] . "</p>" . "<p>" . $row["datum_i_vrijeme"] . "</p>" . "<p>" . "<a class='button1' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=1'>PRIHVATI</a></p><br>" . "<p>" . "<a class='button2' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=0'>ODBIJ</a></p><br>" . "</div>" . "</div>";
        } else {
            if ($brojac % 2 == 0) {
                $content = $content . "<div class='container left'>" . "<div class='content'>" . "<h2>" . $row["naslov_zahtjeva"] . "</h2>" . "<p>" . $row["opsi_zahtjeva"] . "</p>" . "<p>" . $row["vrsta_oglasa"] . "</p>" . "<p>" . $row["datum_i_vrijeme"] . "</p>" . "<p>" . "<a class='button1' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=1'>PRIHVATI</a></p><br>" . "<p>" . "<a class='button2' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=0'>ODBIJ</a></p><br>" . "</div>" . "</div>";
            }
            if ($brojac % 2 != 0) {
                $content = $content . "<div class='container right'>" . "<div class='content'>" . "<h2>" . $row["naslov_zahtjeva"] . "</h2>" . "<p>" . $row["opsi_zahtjeva"] . "</p>" . "<p>" . $row["vrsta_oglasa"] . "</p>" . "<p>" . $row["datum_i_vrijeme"] . "</p>" . "<p>" . "<a class='button1' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=1'>PRIHVATI</a></p><br>" . "<p>" . "<a class='button2' href='blokiranjeZahtjeva.php?idZahtjeva=" . $row["idzahtjev"] . "&operacija=0'>ODBIJ</a></p><br>" . "</div>" . "</div>";
            }
        }
    }
    $brojac++;
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
        
        <link rel="stylesheet" type="text/css" href="../css/timeline.css">
        <title>Zahtjevi za blokiranje oglasa</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="novaDvorana.php">Nova dvorane</a>
                    </li>
                    <li>
                        <a href="termini.php">Termini</a>
                    </li>
                    <li>
                        <a href="zahtjevi.php">Zahtjevi za oglas</a>
                    </li>
                    <li>
                        <a href="zahtjeviBlok.php">Zahtjevi za blok</a>
                    </li>
                    <li>
                        <a href="novaVrsta.php">Nova vrsta oglasa</a>
                    </li>
                    <li>
                        <a href="korisnickiPogled/index.php?odjava=0">Korisnički pogled</a>
                    </li>
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

<?php echo $content; ?>


    </body>
</html>