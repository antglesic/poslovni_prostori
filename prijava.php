<?php
include("sesija.class.php");
include("baza.class.php");

if (Sesija::dajKorisnika() != NULL) {
    header("Location: index.php?odjava=0");
}

$baza = new Baza();
$baza->spojiDB();

$korIme = "";
$lozinka = "";
$ulogaId = 0;
$uloga = "";
$broj_gresaka = "";
$glavnaZastavica = 0;

if (isset($_POST["submit"])) {
    $glavnaZastavica = 1;
    $korIme = $_POST["korime"];
    $lozinka = $_POST["lozinka"];
}

if ($glavnaZastavica === 1) {
    $sql_upit = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korIme . "' AND lozinka LIKE '" . $lozinka . "' AND broj_gresaka < '3' AND status LIKE '1'";
    $rezultat = $baza->selectDB($sql_upit);
    if (mysqli_num_rows($rezultat) > 0) {
        while ($row = $rezultat->fetch_assoc()) {
            $ulogaId = $row["iduloga"];
        }
        if ($ulogaId == 1) {
            $uloga = "admin";
        }
        if ($ulogaId == 2) {
            $uloga = "mod";
        }
        if ($ulogaId == 3) {
            $uloga = "korisnik";
        }
        Sesija::kreirajKorisnika($korIme, $uloga, '1');
        setcookie("Prijava", $korIme, time() + 3600);
        $trenutno = date("Y-m-d H:i:s");
        $opis = "Korisnik " . $korIme . " se prijavio";
        $sql_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('prijava korisnika', '" . $trenutno . "', '" . $opis . "')";
        $rez = $baza->selectDB($sql_dnevnik);
        header("Location: index.php?odjava=0");
    } else {
        $sql_upit_korisnika = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korIme . "'";
        $korisnik = $baza->selectDB($sql_upit_korisnika);
        if (mysqli_num_rows($korisnik) > 0) {
            while ($redak = $korisnik->fetch_assoc()) {
                $broj_gresaka = $redak["broj_gresaka"];
            }
            $broj_gresaka++;
            if($broj_gresaka === 3) {
            	$sql_zakljucaj = "UPDATE korisnik SET status = '2' WHERE korisnicko_ime LIKE '" . $korIme . "'";
            	$zakljucan = $baza->selectDB($sql_zakljucaj);
        	}
            $sql_update_greska = "UPDATE korisnik SET broj_gresaka = '" . $broj_gresaka . "' WHERE korisnicko_ime LIKE '" . $korIme . "'";
            $rez2 = $baza->selectDB($sql_update_greska);
        }
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
        <script type="text/javascript" src="js/vpoljak.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                            var img = '<img class="mySlides" src="slike/' + objekt[property] + '" style="width:100%; height:300px;">'
                            var link2 = '</a>'
                            $('#banner').append(link1 + img + link2);
                        }
                    }
                }
            });

        </script>
        <title>Prijava</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="lokacije.php">Popis lokacija</a>
                    </li>
                    <li>
                        <a href="https://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x119/prijava.php" class="active">Prijava</a>  
                    </li>
                    <li>
                        <a href="registracija.php">Registracija</a>  
                    </li>
                    <li>
                        <a href="oglasi.php">Oglasi</a>
                    </li>
                    <li class="last">
                        <a href="povratnaInformacija.php">Povratna informacija</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="prijava" name="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input name="korime" type="text" placeholder="Korisničko ime"/>
                    <input name="lozinka" type="password" placeholder="Lozinka"/>
                    <input name="submit" class="button" type="submit" id="submit" value="Prijava">
                    <p class="message">Nisi registriran? <a href="registracija.php">Napravi račun</a></p><br>
                    <p class="message"> <a href="zaboravljenaLozinka.php">Zaboravljena lozinka?</a></p>
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

    </body>
</html>
