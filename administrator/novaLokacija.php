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

$sql_upit_moderatori = "SELECT * FROM korisnik WHERE iduloga LIKE '2'";
$moderatori = $baza->selectDB($sql_upit_moderatori);
$selected = "<select id='moderator' name='moderator'>";
while ($row = mysqli_fetch_array($moderatori)) {
    $prikaz = $row['ime'] . " " . $row["prezime"];
    $selected .= "<option value='" . $row['idkorisnik'] . "'>" . $prikaz . "</option>";
}
$selected .= "</select>";

$zastavica = 0;
$nazivLokacije = "";
$adresa = "";
$idModeratora = "";

if(isset($_POST["submit"])) {
    $nazivLokacije = $_POST["naziv"];
    $adresa = $_POST["adresa"];
    $idModeratora = $_POST["moderator"];
    $zastavica = 1;
}

if($zastavica === 1) {
    $sql_insert_lokacija = "INSERT INTO `lokacija`(`adresa`, `ime_lokacije`, `idmoderatora`) VALUES('" . $nazivLokacije . "', '" . $adresa . "', '" . $idModeratora . "')";
    $rez = $baza->selectDB($sql_insert_lokacija);
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('kreirana nova lokacija', '" . $trenutno . "', 'kreirana lokacija " . $nazivLokacije . "')";
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: indexAdmin.php?odjava=0");
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
        <title>Kreiranje lokacije</title>
    </head>
    <body>
        <div id="cssmenu">
            <nav>
                <ul>
                    <li>
                        <a href="index.php?odjava=0">Naslovnica</a>  
                    </li>
                    <li>
                        <a href="novaLokacija.php" class="active">Nova lokacija</a>
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

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="dvorana" name="dvorana" method="POST" action="<?php echo$_SERVER["PHP_SELF"]; ?>">
                    <input id="naziv" name="naziv" type="text" placeholder="Naziv lokacije" />
                    <input id="adresa" name="adresa" type="text" placeholder="Adresa"/>
                    <p id="moderatori">
                        <?php
                        echo $selected;
                        ?>
                    </p><br>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                </form>
            </div>
        </div>

    </body>
</html>