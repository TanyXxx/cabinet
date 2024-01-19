<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUsager = $_POST['idUsager'];
    $idMedecin = $_POST['idMedecin'];
    $dateConsultation = $_POST['dateConsultation'];
    $heureDebut = $_POST['heure'];
    $duree = $_POST['duree'];

    // Calculer l'heure de fin
    $heureFin = date('H:i:s', strtotime($heureDebut) + $duree * 60);

    try {
        // Vérifier le chevauchement
        $sqlChevauchement = "SELECT * FROM consultation 
                             WHERE ID_Medecin = :idMedecin 
                             AND Date_Consultation = :dateConsultation 
                             AND ((Heure <= :heureDebut AND ADDTIME(Heure, SEC_TO_TIME(Duree * 60)) > :heureDebut) 
                             OR (Heure < :heureFin AND ADDTIME(Heure, SEC_TO_TIME(Duree * 60)) >= :heureFin))";
        $stmtChevauchement = $conn->prepare($sqlChevauchement);
        $stmtChevauchement->bindParam(':idMedecin', $idMedecin, PDO::PARAM_INT);
        $stmtChevauchement->bindParam(':dateConsultation', $dateConsultation);
        $stmtChevauchement->bindParam(':heureDebut', $heureDebut);
        $stmtChevauchement->bindParam(':heureFin', $heureFin);
        $stmtChevauchement->execute();

        if ($stmtChevauchement->rowCount() > 0) {
            echo "Impossible d'ajouter la consultation, car elle se chevauche avec une autre.";
        } else {
            // Insérer la consultation
            $sql = "INSERT INTO consultation (ID_USAGER, ID_Medecin, Date_Consultation, Heure, Duree) 
                    VALUES (:idUsager, :idMedecin, :dateConsultation, :heureDebut, :duree)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idUsager', $idUsager, PDO::PARAM_INT);
            $stmt->bindParam(':idMedecin', $idMedecin, PDO::PARAM_INT);
            $stmt->bindParam(':dateConsultation', $dateConsultation);
            $stmt->bindParam(':heureDebut', $heureDebut);
            $stmt->bindParam(':duree', $duree, PDO::PARAM_INT);
            $stmt->execute();
            echo "Consultation enregistrée avec succès.";
        }
    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement de la consultation : " . $e->getMessage());
    }
} else {
    echo "Aucune donnée reçue.";
}
        // Ajout du script JavaScript pour la redirection
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../afficher_consultations.php';
                }, 3000);
              </script>";
?>

