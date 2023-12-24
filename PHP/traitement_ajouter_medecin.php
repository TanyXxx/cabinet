<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $civilite = $_POST['civilite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    try {
        $sql = "INSERT INTO medecin (Civilite, Nom, Prenom) VALUES (:civilite, :nom, :prenom)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);

        $stmt->execute();
        echo "Médecin ajouté avec succès.";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
