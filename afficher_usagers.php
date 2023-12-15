<?php
include 'BD.php'; // Assurez-vous que ce fichier contient les informations de connexion à votre base de données

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $sql = "SELECT * FROM usagers";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Date de Naissance</th><th>Numéro de Sécurité Sociale</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nom'] . "</td>";
            echo "<td>" . $row['prenom'] . "</td>";
            echo "<td>" . $row['adresse'] . "</td>";
            echo "<td>" . $row['date_naissance'] . "</td>";
            echo "<td>" . $row['numero_secu'] . "</td>";
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