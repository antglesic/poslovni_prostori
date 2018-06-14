<?php
include("../../baza.class.php");
include("../../sesija.class.php");

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] === 'admin') {
        header("Location: ../../administrator/index.php");
    }
    if ($_SESSION["uloga"] === 'mod') {
        header("Location: ../../moderator/index.php");
    }
}

$baza = new Baza();
$baza->spojiDB();

$idDvorane = "";
$imeDvorane = "";
$sifraDvorane = "";
$vrsta_koristenja = "";
$email = "";
$zastavica = 0;
$zastavica2 = 0;

if (isset($_GET["idDvorane"])) {
    $idDvorane = $_GET["idDvorane"];
    $zastavica = 1;
}

$sql_upit_dvorana = "SELECT * FROM dvorana WHERE iddvorana LIKE '" . $idDvorane . "'";

if ($zastavica === 1) {
    $dvorana = $baza->selectDB($sql_upit_dvorana);
    while ($row = $dvorana->fetch_assoc()) {
        $imeDvorane = $row["ime_dvorane"];
        $vrsta_koristenja = $row["vrsta_koristenja"];
    }
}

if(isset($_POST["submit"])) {
    header("Location: rezervacija.php?dvorana=" . $_POST["sifraDvorane"] . "&email=" . $_POST["email"]);
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
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <title>Prijava dvorane</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="../index.php?odjava=0" class="active">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="lokacije.php">Popis lokacija</a>
                    </li>
                    <li>
                        <a href="oglasi.php">Oglasi</a>
                    </li>
                    <li>
                        <a href="povratnaInformacija.php">Povratna informacija</a>
                    </li>
                    <li>
                        <a href="o_autoru.html">O autoru</a>
                    </li>
                    <li>
                        <a href="dokumentacija.html">Dokumentacija</a>
                    </li>

                    <li class="last" style="float:right">
                        <a href="../index.php?odjava=1">Odjava</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="prijavaDvorane" name="prijavaDvorane" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <?php
                    echo($imeDvorane . "<br>");
                    echo($vrsta_koristenja . "<br>");
                    ?>
                    <input type="hidden" name='sifraDvorane' value="<?php echo$idDvorane;?>"/>
                    <h2>Prijava dvorane</h2>
                    <input id="email" name="email" type="text" placeholder="Email" />
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>