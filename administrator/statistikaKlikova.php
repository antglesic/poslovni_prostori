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

$sql_upit_statistika = "SELECT zahtjev.datum_i_vrijeme, oglas.naziv_oglasa, oglas.sirina_oglasa, oglas.visina_oglasa, oglas.broj_klikova, korisnik.ime, korisnik.prezime, vrsta_oglasa.vrsta_oglasa FROM zahtjev, oglas, korisnik, vrsta_oglasa WHERE korisnik.idkorisnik LIKE oglas.korisnik_idkorisnik AND oglas.vrsta_oglasa_idvrsta_oglasa LIKE vrsta_oglasa.idvrsta_oglasa AND zahtjev.idoglas LIKE oglas.idoglas AND oglas.status_oglasa LIKE 'Aktivan'";
$statistika = $baza->selectDB($sql_upit_statistika);

$head = "<thead>" . "<tr>" . "<th>Naziv oglasa</th>" . "<th>Širina oglasa</th>" . "<th>Visina oglasa</th>" . "<th>Ime i prezime vlasnika</th>" . "<th>Vrsta oglasa</th>" . "<th>Broj klikova</th>" . "<th>Datum</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $statistika->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["naziv_oglasa"] . "</td>" . "<td>" . $row["sirina_oglasa"] . "</td>" . "<td>" . $row["visina_oglasa"] . "</td>" . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["vrsta_oglasa"] . "</td>" . "<td>" . $row["broj_klikova"] . "</td>" . "<td>" . $row["datum_i_vrijeme"] . "</td>";
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
        <script type="text/javascript">
            $(document).ready(function () {
                var respones = '';
                $.ajax({
                    type: "GET",
                    url: "../banneri.php",
                    async: false,
                    success: function (text) {
                        response = text;
                    }
                });
                for (var i = 0; i < response.length; i++) {
                    var objekt = response[i];
                    for (var property in objekt) {
                        if (property == 'slika') {
                            var link1 = '<a href="' + objekt['link'] + '" target="_blank">';
                            var img = '<img onClick="klik(' + objekt['idoglas'] + ')" class="mySlides" src="../slike/' + objekt[property] + '" style="width:100%; height:300px;">'
                            var link2 = '</a>'
                            $('#banner').append(link1 + img + link2);
                        }
                    }
                }
            });

        </script>
        <title>Statistika klikova oglasa</title>
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
        <div id="banner" class="carousel" style="max-width:500px; max-height: 300px;margin: 0 auto 100px;
             padding: 45px;"></div>
        <link rel="stylesheet" type="text/css" href="../vanjske_biblioteke/slick/slick.css"/>
        <link rel="stylesheet" type="text/css" href="../vanjske_biblioteke/slick/slick-theme.css"/>
        <script type="text/javascript" src="../vanjske_biblioteke/slick/slick.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#banner').slick({
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    adaptiveHeight: true,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: true
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            });
        </script>
        <script type="text/javascript">
            function klik(id) {
                window.location.href = "klikOglas.php?idOglasa=" + id;
            }
        </script>
    </body>
</html>