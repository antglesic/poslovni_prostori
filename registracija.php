<?php
include("baza.class.php");
include("sesija.class.php");

if (Sesija::dajKorisnika() != NULL) {
    header("Location: index.php?odjava=0");
}

$baza = new Baza();
$baza->spojiDB();

//Varijable za vrijednosti
$glavnaZastavica = 0;
$zastavicaUspjeha = 0;
$ime = "";
$prezime = "";
$korIme = "";
$email = "";
$lozinka1 = "";
$lozinka2 = "";
$aktivacijskiLink = "";
$secret = "6LdqZVwUAAAAAEdeEurKcKCRShCfcMOusZepl-hn";
$captcha = "";

//Zastavice za greske
$zastavica1 = 0;
$zastavica2 = 0;
$zastavica3 = 0;
$zastavica4 = 0;
$zastavica5 = 0;
$zastavica6 = 0;
$zastavica7 = 0;


//SQL upiti
$sql_upit_korime = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korIme . "'";
$sql_upit_email = "SELECT * FROM korisnik WHERE email LIKE '" . $email . "'";

if (isset($_POST["submit"])) {
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $korIme = $_POST["korime"];
    $email = $_POST["email"];
    $lozinka1 = $_POST["lozinka1"];
    $lozinka2 = $_POST["lozinka2"];
    $captcha = $_POST['g-recaptcha-response'];
    $glavnaZastavica = 1;
}

if ($glavnaZastavica === 1) {
    if ($ime === '' || $prezime === '' || $korIme === '' || $email === '' || $lozinka1 === '' || $lozinka2 === '') {
        $zastavica1 = 1;
    }
    if ((strpos($ime, "'") !== false) || (strpos($prezime, "'") !== false) || (strpos($korIme, "'") !== false) || (strpos($email, "'") !== false) || (strpos($lozinka1, "'") !== false) || (strpos($lozinka2, "'") !== false) || (strpos($ime, "!") !== false) || (strpos($prezime, "!") !== false) || (strpos($korIme, "!") !== false) || (strpos($email, "!") !== false) || (strpos($lozinka1, "!") !== false) || (strpos($lozinka2, "!") !== false) || (strpos($ime, "?") !== false) || (strpos($prezime, "?") !== false) || (strpos($korIme, "?") !== false) || (strpos($email, "?") !== false) || (strpos($lozinka1, "?") !== false) || (strpos($lozinka2, "?") !== false) || (strpos($ime, "#") !== false) || (strpos($prezime, "#") !== false) || (strpos($korIme, "#") !== false) || (strpos($email, "#") !== false) || (strpos($lozinka1, "#") !== false) || (strpos($lozinka2, "#") !== false)) {
        $zastavica2 = 1;
    }
    if (preg_match("/[a-z0-9]+[_a-z0-9\.-]*[a-z0-9]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})/", $email) === false) {
        $zastavica3 = 1;
    }
    if ($lozinka1 !== $lozinka2) {
        $zastavica4 = 1;
    }
    $rezultat1 = $baza->selectDB($sql_upit_korime);
    if (mysqli_num_rows($rezultat1) > 0) {
        $zastavica5 = 1;
    }
    $rezultat2 = $baza->selectDB($sql_upit_email);
    if (mysqli_num_rows($rezultat2) > 0) {
        $zastavica6 = 1;
    }
    if (!empty($captcha)) {
        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdqZVwUAAAAAEdeEurKcKCRShCfcMOusZepl-hn&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
        if ($response == true) {
            $zastavica7 = 0;
        } else {
            $zastavica7 = 1;
        }
    } else {
        $zastavica7 = 1;
    }
    //Zapisivanje novog korisnika
    if ($zastavica1 === 0 && $zastavica2 === 0 && $zastavica3 === 0 && $zastavica4 === 0 && $zastavica5 === 0 && $zastavica6 === 0 && $zastavica7 === 0) {
        $sol = sha1(time());
        $kriptiranaLozinka = sha1($sol . "--" . $lozinka1);
        $opis = "Korisnik " . $korIme . " se registrirao";
        $datumRegistracije = date("Y-m-d H:i:s");
        $sql_insert_korisnika = "INSERT INTO `korisnik`(`korisnicko_ime`, `lozinka`, `kriptirana_lozinka`, `ime`, `prezime`, `email`, `datum_registracije`, `status`, `iduloga`) VALUES('" . $korIme . "', '" . $lozinka1 . "', '" . $kriptiranaLozinka . "', '" . $ime . "', '" . $prezime . "', '" . $email . "', '" . $datumRegistracije . "', '0', '3')";
        $rez3 = $baza->selectDB($sql_insert_korisnika);
        $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('registracija korisnika', '" . $datumRegistracije . "', '" . $opis . "')";
        $rez4 = $baza->selectDB($sql_insert_dnevnik);
        $aktivacijskiLink = "<a href='http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x119/aktivacija.php?korisnik=" . $korIme . "&aktikod=" . $kriptiranaLozinka . "'>Aktivirajte svoj racun</a>";
        $zastavicaUspjeha = 1;
    }
    if ($zastavicaUspjeha === 1) {
        $mail_to = $email;   //$_POST["email"];
        $mail_from = "From: WebDiP_2017@foi.hr";
        $mail_subject = "Aktivacija registracije";      //$_POST["subjekt"];
        $link = "http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x119/aktivacija.php?korisnik=" . $korIme . "&aktikod=" . $kriptiranaLozinka;
        $mail_body = "Pritisnite na iduci link kako biste aktivirali vas racun: <a href=" . $link . ">" . $link . "</a>";

        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            $povratna_informacija1 = "Uspjesno ste registrirali vas racun!" . "<br>";
        } else {
            $povratna_informacija1 = "Problem kod registracije korisnickog racuna!" . "<br>";
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
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/vpoljak.js"></script>
        <title>Registracija</title>
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
                        <a href="https://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x119/prijava.php">Prijava</a>  
                    </li>
                    <li>
                        <a href="registracija.php" class="active">Registracija</a>  
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

        <div>
            <?php
            if ($zastavicaUspjeha === 1) {
                echo($aktivacijskiLink . "<br>");
            }
            ?>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="registracija" name="registracija" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input id="ime" name="ime" type="text" placeholder="Ime" />
                    <input id="prezime" name="prezime" type="text" placeholder="Prezime" />
                    <input id="korime" name="korime" type="text" placeholder="Korisničko ime"/>
                    <input id="email" name="email" type="text" placeholder="Email"/>
                    <input id="lozinka1" name="lozinka1" type="password" placeholder="Lozinka"/>
                    <input id="lozinka2" name="lozinka2" type="password" placeholder="Ponovljena lozinka"/>
                    <div class="g-recaptcha" data-sitekey="6LdqZVwUAAAAAEdeEurKcKCRShCfcMOusZepl-hn"></div>
                    <input name="submit" class="button" type="submit" id="submit" value="Registracija">
                    <p class="message">Već imaš račun? <a href="prijava.php">Prijava</a></p>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                var simb1 = "!";
                var simb2 = "?";
                var simb3 = "#";
                $('#ime').keyup(function () {
                    var ime = $("#ime").val();
                    var zastavica = 0;
                    if (ime.indexOf(simb1) != -1) {
                        zastavica = 1;
                    }
                    if (ime.indexOf(simb2) != -1) {
                        zastavica = 1;
                    }
                    if (ime.indexOf(simb3) != -1) {
                        zastavica = 1;
                    }
                    if (zastavica === 0) {
                        $("#submit").removeClass("onemoguceno");
                    } else {
                        $("#submit").addClass("onemoguceno");
                    }
                });

                $('#prezime').keyup(function () {
                    var prezime = $("#prezime").val();
                    var zastavica = 0;
                    if (prezime.indexOf(simb1) != -1) {
                        zastavica = 1;
                    }
                    if (prezime.indexOf(simb2) != -1) {
                        zastavica = 1;
                    }
                    if (prezime.indexOf(simb3) != -1) {
                        zastavica = 1;
                    }
                    if (zastavica === 0) {
                        $("#submit").removeClass("onemoguceno");
                    } else {
                        $("#submit").addClass("onemoguceno");
                    }
                });



                $('#korime').keyup(function () {
                    var zastavica = 0;
                    var response = '';
                    $.ajax({
                        type: "GET",
                        url: "korisnici.php",
                        async: false,
                        success: function (text) {
                            response = text;
                        }
                    });
                    console.log(response);
                    var korime = $("#korime").val();
                    console.log(response.indexOf(korime));
                    if (response.indexOf(korime) != -1) {
                        zastavica = 1;
                    } else {
                        zastavica = 0;
                    }
                    if (zastavica === 1) {
                        $("#submit").addClass("onemoguceno");
                    } else {
                        $("#submit").removeClass("onemoguceno");
                    }
                });

                $('#email').keyup(function () {
                    var zastavica = 0;
                    var email = $("#email").val();
                    var reg = new RegExp(/^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);
                    if (reg.test(email)) {
                        zastavica = 0;
                    } else {
                        zastavica = 1;
                    }
                    if (zastavica === 1) {
                        $("#submit").addClass("onemoguceno");
                    } else {
                        $("#submit").removeClass("onemoguceno");
                    }
                });

                $('#lozinka2').keyup(function () {
                    var zastavica = 0;
                    var lozinka1 = $("#lozinka1").val();
                    var lozinka2 = $("#lozinka2").val();
                    if (lozinka2 !== lozinka1) {
                        zastavica = 1;
                    } else {
                        zastavica = 0;
                    }
                    if (zastavica === 1) {
                        $("#submit").addClass("onemoguceno");
                    } else {
                        $("#submit").removeClass("onemoguceno");
                    }
                });
            });
        </script>
    </body>
</html>