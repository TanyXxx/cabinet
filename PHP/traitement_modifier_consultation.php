<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $dateConsultation = $_POST['dateConsultation'];
    $heure = $_POST['heure'];
    $duree = $_POST['duree'];

    try {
        $sql = "UPDATE consultation SET Date_Consultation = :dateConsultation, Heure = :heure, Duree = :duree WHERE ID_Consultation = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':dateConsultation', $dateConsultation);
        $stmt->bindParam(':heure', $heure);
        $stmt->bindParam(':duree', $duree, PDO::PARAM_INT);

        $stmt->execute();
        echo "Consultation modifiée avec succès.";
        header('Location: ../afficher_consultations.php');
    } catch (PDOException $e) {
        die("Erreur lors de la modification de la consultation : " . $e->getMessage());
    }
}
?>
