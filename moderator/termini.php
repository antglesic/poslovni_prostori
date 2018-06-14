<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if(isset($_SESSION["korisnik"])) {
    if($_SESSION["uloga"] === 'admin') {
        header("Location: ../administrator/index.php");
    }
    if($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../korisnik/index.php");
    }
}

$baza = new Baza();
$baza->spojiDB();

$korisnikID = "";
$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $baza->selectDB($sql_upit_korisnik);
while($row = $korisnik->fetch_assoc()) {
    $korisnikID = $row["idkorisnik"];
}

$sql_upit_termini = "SELECT dvorana.ime_dvorane, dvorana.vrsta_koristenja, prijavljena_dvorana.trajanje, prijavljena_dvorana.datum, prijavljena_dvorana.idprijave FROM dvorana, prijavljena_dvorana, lokacija WHERE lokacija.idmoderatora LIKE '" . $korisnikID . "' AND dvorana.idlokacija LIKE lokacija.idlokacija AND dvorana.iddvorana LIKE prijavljena_dvorana.iddvorana";
$termini = $baza->selectDB($sql_upit_termini);

$head = "<thead>" . "<tr>" . "<th>Ime dvorane</th>" . "<th>Svrha korištenja</th>" . "<th>Trajanje (dani)</th>" . "<th>Datum</th>" . "<th>Otkazivanje termina</th>" . "</tr>" . "</thead>";
$table = "";
while ($row = $termini->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["ime_dvorane"] . "</td>" . "<td>" . $row["vrsta_koristenja"] . "</td>" . "<td>" . $row["trajanje"] . "<td>" . $row["datum"] . "</td>" . "</td>" . "<td><a href='otkazTermina.php?idPrijave=" . $row["idprijave"] . "'>OTKAŽI" ."</a></td>";
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
        <title>Termini</title>
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