<?php
include("../../baza.class.php");
include("../../sesija.class.php");

Sesija::kreirajSesiju();

if(isset($_SESSION["korisnik"])) {
    if($_SESSION["uloga"] === 'admin') {
        header("Location: ../../administrator/index.php");
    }
    if($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../../korisnik/index.php");
    }
}

$baza = new Baza();
$baza->spojiDB();

$idoglasa = "";
$imeoglasa = "";
$sifraDvorane = "";
$email = "";
$zastavica = 0;
$zastavica2 = 0;

if (isset($_GET["idOglas"])) {
    $idoglasa = $_GET["idOglas"];
    $zastavica = 1;
}

$sql_upit_oglas = "SELECT * FROM oglas WHERE idoglas LIKE '" . $_GET["idOglas"] . "'";
$rezultat = $baza->selectDB($sql_upit_oglas);
while($redak = $rezultat->fetch_assoc()) {
    $imeoglasa = $redak["naziv_oglasa"];
}

if ($zastavica === 1) {
    $oglas = $baza->selectDB($sql_upit_oglas);
    while ($row = $oglas->fetch_assoc()) {
        $imeoglasa = $row["naziv_oglasa"];
    }
}

if(isset($_POST["submit"])) {
    header("Location: oglasBlok.php?oglas=" . $_POST["sifraOglasa"] . "&razlog=" . $_POST["razlog"]);
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
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../../js/vpoljak.js"></script>
        <title>Blokiranje oglasa</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="oglasi.php" class="active">Blokiranje oglasa</a>
                    </li>
                    <li>
                        <a href="vrsteOglasa.php">Vrste oglasa</a>
                    </li>
                    <li>
                        <a href="zahtjevi.php">Vaši zahtjevi</a>
                    </li>
                    <li>
                        <a href="vasiOglasi.php">Vaši oglasi</a>
                    </li>
                    <li class="last" style="float:right">
                        <a href="index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="prijavaDvorane" name="prijavaDvorane" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <?php
                    echo($imeoglasa . "<br>");
                    ?>
                    <input type="hidden" name='sifraOglasa' value="<?php echo$idoglasa;?>"/>
                    <h2>Blokiranje oglasa</h2>
                    <input id="razlog" name="razlog" type="text" placeholder="Razlog" />
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>