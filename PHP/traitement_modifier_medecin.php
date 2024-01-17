<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $civilite = $_POST['civilite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    try {
        $sql = "UPDATE medecin SET Civilite = :civilite, Nom = :nom, Prenom = :prenom WHERE ID_Medecin = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);

        $stmt->execute();
        echo "Médecin modifié avec succès.";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
