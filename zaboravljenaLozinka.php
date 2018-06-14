<?php
include("baza.class.php");
include("sesija.class.php");

if (Sesija::dajKorisnika() != NULL) {
    header("Location: index.php?odjava=0");
}

$baza = new Baza();
$baza->spojiDB();

$korIme = "";
$lozinka = "";
$mail = "";
$zastavica = 0;

if (isset($_POST["submit"])) {
    $korIme = $_POST["korime"];
    $zastavica = 1;
}

if ($zastavica === 1) {
    $sql_upit_korisnika = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korIme . "'";
    $rez = $baza->selectDB($sql_upit_korisnika);
    if (mysqli_num_rows($rez) > 0) {
        while ($row = $rez->fetch_assoc()) {
            $lozinka = $row["lozinka"];
            $mail = $row["email"];
        }
        $salt = sha1(time());
        $nova_lozinka = sha1($salt . "--" . $lozinka);
        $sql_update_lozinka = "UPDATE korisnik SET lozinka = '" . $nova_lozinka . "' WHERE korisnicko_ime LIKE '" . $korIme . "'";
        $rez1 = $baza->selectDB($sql_update_lozinka);
        $mail_to = $mail;
        $mail_from = "From: WebDiP_2017@foi.hr";
        $mail_subject = "Nova Lozinka";
        $mail_body = "Vaša nova lozinka izgleda ovako: " . $nova_lozinka;
        if(mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            header("Location: prijava.php");
        }
    } else {
        header("Location: prijava.php");
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
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <title>Zaboravljena lozinka</title>
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
                <form class="login-form" id="zabLozinka" name="zabLozinka" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input id="korime" name="korime" type="text" placeholder="Korisničko ime" />
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>