<?php
include 'BD.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $sql = "SELECT * FROM usagers";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            echo $row['nom'] . " " . $row['prenom'] . " - <a href='modifier_usager.php?id=" . $row['id'] . "'>Modifier</a><br>";
        }
    } else {
        echo "Aucun usager trouvé.";
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>