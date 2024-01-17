<?php
include 'BD.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM consultation WHERE ID_Consultation = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Consultation supprimée avec succès.";
        header('Location: ../afficher_consultations.php');
    } catch (PDOException $e) {
        die("Erreur lors de la suppression de la consultation : " . $e->getMessage());
    }
} else {
    echo "ID de consultation non spécifié.";
}
?>
