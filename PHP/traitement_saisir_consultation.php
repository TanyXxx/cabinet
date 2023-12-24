<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUsager = $_POST['idUsager'];
    $dateConsultation = $_POST['dateConsultation'];
    $heure = $_POST['heure'];
    $duree = $_POST['duree'];

    try {
        $sql = "INSERT INTO consultation (ID_USAGER, Date_Consultation, Heure, Durée) VALUES (:idUsager, :dateConsultation, :heure, :duree)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':idUsager', $idUsager, PDO::PARAM_INT);
        $stmt->bindParam(':dateConsultation', $dateConsultation);
        $stmt->bindParam(':heure', $heure);
        $stmt->bindParam(':duree', $duree, PDO::PARAM_INT);

        $stmt->execute();
        echo "Consultation enregistrée avec succès.";
    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement de la consultation : " . $e->getMessage());
    }
} else {
    echo "Aucune donnée reçue.";
}
?>
