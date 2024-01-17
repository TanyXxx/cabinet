<?php
include 'BD.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM medecin WHERE ID_Medecin = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Médecin supprimé avec succès.";
        header('Location: ../afficher_medecins.php');
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "ID de médecin non spécifié.";
}
?>
