<?php
include 'BD.php';
$sql = "SELECT ID_USAGER, Nom, Prenom FROM usager";
foreach ($conn->query($sql) as $row) {
    echo "<option value='" . $row['ID_USAGER'] . "'>" . $row['Nom'] . " " . $row['Prenom'] . "</option>";
}
?>
