<?php
include 'BD.php';

try {
    $sql = "SELECT * FROM medecin";
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() > 0) {
        echo "<table>";
        echo "<tr><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Actions</th></tr>";

        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Civilite']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Prenom']) . "</td>";
            echo "<td><a href='modifier_medecin.php?id=" . $row['ID_Medecin'] . "'>Modifier</a> | <a href='supprimer_medecin.php?id=" . $row['ID_Medecin'] . "'>Supprimer</a></td>";
            echo "</tr>";
        }

        echo "</table>";
        ?>
<div class="div-btn-liste-consultation">    
        <?php
        echo "<button class='btn-ajouter-consultation'onclick=\"window.location.href='ajouter_medecin.php'\">Ajouter Medecin</button>";}
        ?>
</div>
<?php
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
