<?php
require_once '../BD/connexionDB.php';

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
        try {
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
        } catch (PDOException $e) {
            deliver_response(500, "Erreur : " . $e->getMessage());
            return; // Arrêt de la fonction en cas d'erreur
        }
    }
    deliver_response(200, "Statistiques usagers récupérées avec succès.", $response);
}

function getStatistiquesMedecins() {
    global $conn;
    $response = [];
    $sql = "SELECT medecin.Nom, medecin.Prenom, SUM(consultation.Duree) as TotalDuree 
            FROM consultation 
            JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin 
            GROUP BY consultation.ID_Medecin";
    try {
        $stmt = $conn->query($sql);
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nomMedecin = $ligne['Nom'] . " " . $ligne['Prenom'];
            $totalMinutes = (int)$ligne['TotalDuree'];
            $totalHeures = round($totalMinutes / 60.0, 2); // Conversion des minutes en heures avec arrondi à 2 décimales
            $response[] = ['Medecin' => $nomMedecin, 'TotalHeures' => $totalHeures];
        }
        deliver_response(200, "Statistiques médecins récupérées avec succès.", $response);
    } catch (PDOException $e) {
        deliver_response(500, "Erreur : " . $e->getMessage());
    }
}

function deliver_response($status_code, $status_message, $data = null)
{
    http_response_code($status_code);
    header("Content-Type:application/json; charset=utf-8");

    $status_phrases = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    $response = [
        'status_code' => $status_code,
        'status' => isset($status_phrases[$status_code]) ? $status_phrases[$status_code] : 'Unknown Status',
        'status_message' => $status_message,
        'data' => $data,
    ];

    $json_response = json_encode($response, JSON_UNESCAPED_UNICODE);
    if ($json_response === false) {
        die('json encode ERROR : ' . json_last_error_msg());
    }

    echo $json_response;
}

