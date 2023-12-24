<?php
include 'BD.php';

try {
    $sql = "SELECT * FROM usager"; // Assurez-vous que le nom de la table est correct
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Lieu de Naissance</th><th>Date de Naissance</th><th>Numéro de Sécurité Sociale</th><th>Actions</th></tr>";

        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_USAGER']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Civilite']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Prenom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Adresse']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Lieu_Naissance']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Date_Naissance']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Numero_Secu']) . "</td>";
            echo "<td><a href='../modifier_usager.php?id=" . $row['ID_USAGER'] . "'>Modifier</a> | <a href='../supprimer_usager.php?id=" . $row['ID_USAGER'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet usager ?\");'>Supprimer</a></td>";
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
