<?php
include("baza.class.php");
include("sesija.class.php");

if (Sesija::dajKorisnika() != NULL) {
    header("Location: index.php?odjava=0");
}

$sigurnosniKod = "";
$povratnaTekst = "";
$iddvorane = "";
$zastavica1 = 0;

$baza = new Baza();
$baza->spojiDB();

if (isset($_POST["submit"])) {
    $sigurnosniKod = $_POST["kod"];
    $povratnaTekst = $_POST["povratna"];
    $zastavica1 = 1;
}

if ($zastavica1 === 1) {
    $sql_upit_prijave = "SELECT * FROM prijavljena_dvorana WHERE sigurnosniKod LIKE '" . $sigurnosniKod . "'";
    $rez1 = $baza->selectDB($sql_upit_prijave);
    if (mysqli_num_rows($rez1) > 0) {
        while ($row = $rez1->fetch_assoc()) {
            $iddvorane = $row["iddvorana"];
        }
        $sql_update_prijava = "UPDATE prijavljena_dvorana SET povratnaInformacija = '" . $povratnaTekst . "' WHERE sigurnosniKod LIKE '" . $sigurnosniKod . "'";
        $rez2 = $baza->selectDB($sql_update_prijava);
        $sql_update_zauzeto = "UPDATE dvorana SET zauzeto = '0' WHERE iddvorana LIKE '" . $iddvorane . "'";
        $rez3 = $baza->selectDB($sql_update_zauzeto);
        $trenutno = date("Y-m-d H:i:s");
        $opis = "Ostavljena je povratna informacija za rezervaciju dvorane " . $iddvorane;
        $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('povratna informacija', '" . $trenutno . "', '" . $opis . "')";
        $rez4 = $baza->selectDB($sql_insert_dnevnik);
        header("Location: index.php?odjava=0");
    }
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
        <link rel="stylesheet" type="text/css" href="css/vpoljak_main.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/vpoljak.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var respones = '';
                $.ajax({
                    type: "GET",
                    url: "banneri.php",
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
                            var img = '<img onClick="klik('+objekt['idoglas']+')" class="mySlides" src="slike/' + objekt[property] + '" style="width:100%; height:300px;">'
                            var link2 = '</a>'
                            $('#banner').append(link1 + img + link2);
                        }
                    }
                }
            });

        </script>
        <title>Povratna informacija</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="lokacije.php" >Popis lokacija</a>
                    </li>
                    <li>
                        <a href="https://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x119/prijava.php">Prijava</a>  
                    </li>
                    <li>
                        <a href="registracija.php">Registracija</a>  
                    </li>
                    <li>
                        <a href="oglasi.php">Oglasi</a>
                    </li>
                    <li class="last">
                        <a href="povratnaInformacija.php" class="active">Povratna informacija</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="povratnaInformacija" name="povratnaInformacija" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input id="kod" name="kod" type="text" placeholder="Kod"/>
                    <textarea name='povratna' id='povratna' cols="50" rows="3"></textarea>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

        <div id="banner" class="carousel" style="max-width:500px; max-height: 300px;margin: 0 auto 100px;
             padding: 45px;"></div>
        <link rel="stylesheet" type="text/css" href="vanjske_biblioteke/slick/slick.css"/>
        <link rel="stylesheet" type="text/css" href="vanjske_biblioteke/slick/slick-theme.css"/>
        <script type="text/javascript" src="vanjske_biblioteke/slick/slick.min.js"></script>
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