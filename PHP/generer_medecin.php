<?php
include 'BD.php';
$sql = "SELECT ID_Medecin, Nom, Prenom FROM medecin";
foreach ($conn->query($sql) as $row) {
    $selected = ($row['ID_Medecin'] == $consultation['ID_Medecin']) ? 'selected' : '';
    echo "<option value='" . $row['ID_Medecin'] . "' $selected>" . $row['Nom'] . " " . $row['Prenom'] . "</option>";
}
?>
