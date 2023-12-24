<?php
include 'BD.php';

try {
    // Ici, vous devrez écrire les requêtes SQL pour obtenir les statistiques des consultations par médecin
    // ...

    echo "<h2>Durée totale des consultations par médecin</h2>";
    echo "<table>";
    echo "<tr><th>Médecin</th><th>Durée totale (heures)</th></tr>";

    // Affichage des résultats
    // ...

    echo "</table>";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
