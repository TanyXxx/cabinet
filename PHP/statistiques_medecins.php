<?php
include 'BD.php';

try {
    echo "<h2>Durée totale des consultations par médecin</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Médecin</th><th>Durée totale (heures)</th></tr>";

    // Remplacez 'ID_Medecin' par la colonne appropriée si différente dans votre table de consultation
    $sql = "SELECT medecin.Nom, medecin.Prenom, SUM(consultation.Duree) as TotalDuree 
            FROM consultation 
            JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin 
            GROUP BY consultation.ID_Medecin";
    $stmt = $conn->query($sql);

    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nomMedecin = $ligne['Nom'] . " " . $ligne['Prenom'];
        $totalHeures = $ligne['TotalDuree'];
        echo "<tr><td>$nomMedecin</td><td>$totalHeures</td></tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

?>
