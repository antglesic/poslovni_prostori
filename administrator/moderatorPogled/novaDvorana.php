<?php
include("../../baza.class.php");
include("../../sesija.class.php");

Sesija::kreirajSesiju();

if(isset($_SESSION["korisnik"])) {
    if($_SESSION["uloga"] === 'mod') {
        header("Location: ../../moderator/index.php");
    }
    if($_SESSION["uloga"] === 'korisnik') {
        header("Location: ../../korisnik/index.php");
    }
}
$baza = new Baza();
$baza->spojiDB();

$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $baza->selectDB($sql_upit_korisnik);

$korisnikID = "";
while ($row = $korisnik->fetch_assoc()) {
    $korisnikID = $row["idkorisnik"];
}

$sql_upit_lokacije = "SELECT * FROM lokacija WHERE idmoderatora LIKE '" . $korisnikID . "'";
$lokacije = $baza->selectDB($sql_upit_lokacije);
$selected = "<select id='lokacija' name='lokacija'>";
while ($row = mysqli_fetch_array($lokacije)) {
    $prikaz = $row['ime_lokacije'];
    $selected .= "<option value='" . $row['idlokacija'] . "'>" . $prikaz . "</option>";
}
$selected .= "</select>";

$zastavica = 0;
$imeDvorane = "";
$brojMjesta = "";
$vrstaKoristenja = "";
$idLokacije = "";

if(isset($_POST["submit"])) {
    $imeDvorane = $_POST["naziv"];
    $brojMjesta = $_POST["brojMjesta"];
    $vrstaKoristenja = $_POST["vrstaKoristenja"];
    $idLokacije = $_POST["lokacija"];
    $zastavica = 1;
}

if($zastavica === 1) {
    $sql_insert_dvorana = "INSERT INTO `dvorana`(`ime_dvorane`, `broj_mjesta`, `vrsta_koristenja`, `idlokacija`, `zauzeto`) VALUES('" . $imeDvorane . "', '" . $brojMjesta . "', '" . $vrstaKoristenja . "', '" . $idLokacije . "', '0')";
    $rez = $baza->selectDB($sql_insert_dvorana);
    $trenutno = date("Y-m-d H:i:s");
    $sql_insert_dnevnik = "INSERT INTO `dnevnik`(`naziv_akcije`, `datum_vrijeme`, `opis`) VALUES('kreirana nova dvorana', '" . $trenutno . "', 'kreirana dvorana " . $imeDvorane . "')";
    $rez2 = $baza->selectDB($sql_insert_dnevnik);
    header("Location: noviTermin.php");
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
        <title>Kreiranje dvorane</title>
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

        <div class="login-page">
            <div class="form">
                <form class="login-form" id="dvorana" name="dvorana" method="POST" action="<?php echo$_SERVER["PHP_SELF"]; ?>">
                    <input id="naziv" name="naziv" type="text" placeholder="Ime dvorane" />
                    <input id="mjesta" name="brojMjesta" type="text" placeholder="Broj mjesta"/>
                    <input id="vrsta" name="vrstaKoristenja" type="text" placeholder="Vrsta korištenja"/>
                    <p id="lokacije">
                        <?php
                        echo $selected;
                        ?>
                    </p><br>
                    <input name="submit" class="button" type="submit" id="submit" value="Pošalji">
                    <p class="message">Već kreirana dvorana? <a href="noviTermin.php">Napravi termin</a></p><br>
                </form>
            </div>
        </div>

    </body>
</html>