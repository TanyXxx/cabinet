<?php
include 'BD.php';

try {
    $sql = "SELECT * FROM usager";
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() > 0) {
        echo "<table>";
        echo "<div class='test'><tr><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Lieu de Naissance</th><th>Date de Naissance</th><th>Numéro de Sécurité Sociale</th><th>Actions</th></tr></div>";

        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Civilite']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Prenom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Adresse']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Lieu_Naissance']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Date_Naissance']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Numero_Secu']) . "</td>";
            echo "<td><a href='modifier_usager.php?id=" . $row['ID_USAGER'] . "'>Modifier</a> | <a href='supprimer_usager.php?id=" . $row['ID_USAGER'] . "'>Supprimer</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucun usager trouvé.";
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
