<?php
include 'BD.php';

try {
    echo "<h2>Répartition des usagers selon leur sexe et leur âge</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Tranche d'âge</th><th>Nb Hommes</th><th>Nb Femmes</th></tr>";

    // Calcul de la répartition par tranche d'âge et sexe
    $tranches = [
        'Moins de 25 ans' => 'YEAR(CURDATE()) - YEAR(Date_Naissance) < 25',
        'Entre 25 et 50 ans' => 'YEAR(CURDATE()) - YEAR(Date_Naissance) BETWEEN 25 AND 50',
        'Plus de 50 ans' => 'YEAR(CURDATE()) - YEAR(Date_Naissance) > 50'
    ];

    foreach ($tranches as $tranche => $condition) {
        $sql = "SELECT Civilite, COUNT(*) as Nombre FROM usager WHERE $condition GROUP BY Civilite";
        $stmt = $conn->query($sql);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $nbHommes = $nbFemmes = 0;
        foreach ($resultats as $ligne) {
            if ($ligne['Civilite'] == 'Monsieur') {
                $nbHommes = $ligne['Nombre'];
            } elseif ($ligne['Civilite'] == 'Madame') {
                $nbFemmes = $ligne['Nombre'];
            }
        }

        echo "<tr><td>$tranche</td><td>$nbHommes</td><td>$nbFemmes</td></tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
