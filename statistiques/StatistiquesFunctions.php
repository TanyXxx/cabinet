<?php
require_once '../connexionDB.php';

function getStatistiquesUsagers() {
    global $conn;
    $response = [];
    $tranches = [
        'Moins de 25 ans' => 'YEAR(CURDATE()) - YEAR(Date_Naissance) < 25',
        'Entre 25 et 50 ans' => 'YEAR(CURDATE()) - YEAR(Date_Naissance) BETWEEN 25 AND 50',
        'Plus de 50 ans' => 'YEAR(CURDATE()) - YEAR(Date_Naissance) > 50'
    ];

    foreach ($tranches as $tranche => $condition) {
        $sql = "SELECT Sexe, COUNT(*) as Nombre FROM usager WHERE $condition GROUP BY Sexe";
        $stmt = $conn->query($sql);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = ['Hommes' => 0, 'Femmes' => 0];
        foreach ($resultats as $ligne) {
            if ($ligne['Sexe'] == 'H') {
                $stats['Hommes'] = (int)$ligne['Nombre'];
            } elseif ($ligne['Sexe'] == 'F') {
                $stats['Femmes'] = (int)$ligne['Nombre'];
            }
        }
        $response[] = ['Tranche' => $tranche] + $stats;
    }
    return $response;
}


function getStatistiquesMedecins() {
    global $conn;
    $response = [];
    $sql = "SELECT medecin.Nom, medecin.Prenom, SUM(consultation.Duree) as TotalDuree 
            FROM consultation 
            JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin 
            GROUP BY consultation.ID_Medecin";
    $stmt = $conn->query($sql);
    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nomMedecin = $ligne['Nom'] . " " . $ligne['Prenom'];
        $totalHeures = (int)$ligne['TotalDuree'];
        $response[] = ['Medecin' => $nomMedecin, 'TotalHeures' => $totalHeures];
    }
    return $response;
}

