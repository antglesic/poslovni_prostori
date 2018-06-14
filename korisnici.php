<?php
include("baza.class.php");

$baza = new Baza();
$baza->spojiDB();

$sql_upit = "SELECT * FROM korisnik";
$rez = $baza->selectDB($sql_upit);
?>
<table>
    <thead>
        <tr>
            <th>
                Korisničko ime
            </th>
            <th>
                Email
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $rez->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["korisnicko_ime"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "</tr>";
        }

        $baza->zatvoriDB();
        ?>
    </tbody>
</table>


