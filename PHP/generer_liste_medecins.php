<?php
include 'BD.php';
$sql = "SELECT ID_Medecin, Nom, Prenom FROM medecin";
foreach ($conn->query($sql) as $row) {
    echo "<option value='" . $row['ID_Medecin'] . "'>" . $row['Nom'] . " " . $row['Prenom'] . "</option>";
}
?>
