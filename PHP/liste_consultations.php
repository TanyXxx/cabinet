<?php
include 'BD.php';

try {
    $sql = "SELECT consultation.ID_Consultation, usager.Nom as UsagerNom, usager.Prenom as UsagerPrenom, medecin.Nom as MedecinNom, medecin.Prenom as MedecinPrenom, consultation.Date_Consultation, consultation.Heure, consultation.Duree 
            FROM consultation 
            JOIN usager ON consultation.ID_USAGER = usager.ID_USAGER 
            JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin
            ORDER BY consultation.Date_Consultation DESC, consultation.Heure DESC"; 
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Usager</th><th>Médecin</th><th>Date de Consultation</th><th>Heure</th><th>Durée (minutes)</th></tr>";

        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_Consultation']) . "</td>";
            echo "<td>" . htmlspecialchars($row['UsagerNom']) . " " . htmlspecialchars($row['UsagerPrenom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['MedecinNom']) . " " . htmlspecialchars($row['MedecinPrenom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Date_Consultation']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Heure']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Duree']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucune consultation trouvée.";
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
