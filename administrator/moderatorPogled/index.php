<?php
include("../../baza.class.php");
include("../../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] === 'mod') {
        header("Location: ../../moderator/index.php");
    }
    if ($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../../korisnik/index.php");
    }
}
$baza = new Baza();
$baza->spojiDB();
$odjava = 0;

if (isset($_GET["odjava"])) {
    $odjava = $_GET["odjava"];
}

if ($odjava == 1) {
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('odjava korisnika', '" . $trenutno . "', 'odjavljen korisnik " . $_SESSION["korisnik"] . "')";
    $rez = $baza->selectDB($sql_insert_dnevnik);
    Sesija::obrisiSesiju();
    header("Location: ../../index.php?odjava=0");
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
        <link rel="stylesheet" type="text/css" href="../../css/vpoljak_main.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var respones = '';
                $.ajax({
                    type: "GET",
                    url: "../../banneri.php",
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
                            var img = '<img class="mySlides" src="../../slike/' + objekt[property] + '" style="width:100%; height:300px;">'
                            var link2 = '</a>'
                            $('#banner').append(link1 + img + link2);
                        }
                    }
                }
            });

        </script>
        <title>Naslovnica</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="../index.php?odjava=0" class="active">Naslovnica</a>  
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
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div id="banner" class="carousel" style="max-width:500px; max-height: 300px;margin-left: 5%;margin-top: 5%;"></div>
        <link rel="stylesheet" type="text/css" href="../../vanjske_biblioteke/slick/slick.css"/>
        <link rel="stylesheet" type="text/css" href="../../vanjske_biblioteke/slick/slick-theme.css"/>
        <script type="text/javascript" src="../../vanjske_biblioteke/slick/slick.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#banner').slick({
                    dots: true,
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
    </body>
</html>