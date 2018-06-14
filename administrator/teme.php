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

$tema = "";
$zastavica = 0;

if (isset($_POST["submit"])) {
    $tema = $_POST["tema"];
    $zastavica = 1;
}

if ($zastavica === 1) {
    if ($tema == '1') {
        $fh = fopen('../css/vpoljak_main.css', 'w');
        fclose($fh);
        $fs = fopen("../css/classicTheme.css", "r");
        $ft = fopen("../css/vpoljak_main.css", "w");
        while ($ch = fgets($fs))
            fputs($ft, $ch);
        fclose($fs);
        fclose($ft);
        header("Location: index.php?odjava=0");
    }
    if ($tema == '2') {
        $fh = fopen('../css/vpoljak_main.css', 'w');
        fclose($fh);
        $fs = fopen("../css/darkerTheme.css", "r");
        $ft = fopen("../css/vpoljak_main.css", "w");
        while ($ch = fgets($fs))
            fputs($ft, $ch);
        fclose($fs);
        fclose($ft);
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
        <link rel="stylesheet" type="text/css" href="../css/vpoljak_main.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/vpoljak.js"></script>
        <title>Tema aplikacije</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="korisnici.php">Upravljanje računima</a>
                    </li>
                    <li>
                        <a href="dnevnik.php">Dnevnik</a>
                    </li>
                    <li>
                        <a href="moderatori.php">Moderatori</a>
                    </li>
                    <li>
                        <a href="teme.php">Tema</a>
                    </li>
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="teme" name="teme" method="POST" action="<?php echo$_SERVER["PHP_SELF"]; ?>">
                    <select name="tema" id="tema">
                        <option value="1" selected="selected">Svijetla tema</option>
                        <option value="2">Tamna tema</option>
                    </select>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>