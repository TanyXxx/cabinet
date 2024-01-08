<?php
include 'BD.php';
$sql = "SELECT ID_USAGER, Nom, Prenom FROM usager";
foreach ($conn->query($sql) as $row) {
    $selected = ($row['ID_USAGER'] == $consultation['ID_USAGER']) ? 'selected' : '';
    echo "<option value='" . $row['ID_USAGER'] . "' $selected>" . $row['Nom'] . " " . $row['Prenom'] . "</option>";
}
?>
