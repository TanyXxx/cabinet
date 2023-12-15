<?php
include 'BD.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $sql = "DELETE FROM usagers WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        echo "Usager supprimé avec succès.";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "ID d'usager non spécifié.";
}
?>