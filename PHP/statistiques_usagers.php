<?php
include 'BD.php';

try {
    // Ici, vous devrez écrire les requêtes SQL pour obtenir les statistiques désirées
    // Exemple de requête pour obtenir le nombre d'hommes et de femmes dans chaque tranche d'âge
    // ...

    echo "<h2>Répartition des usagers selon leur sexe et leur âge</h2>";
    echo "<table>";
    echo "<tr><th>Tranche d'âge</th><th>Nb Hommes</th><th>Nb Femmes</th></tr>";

    // Affichage des résultats
    // ...

    echo "</table>";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
