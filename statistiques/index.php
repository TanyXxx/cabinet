<?php
require_once 'StatistiquesFunctions.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if (isset($_GET['type'])) {
    if ($_GET['type'] == 'usagers') {
        echo json_encode(getStatistiquesUsagers());
    } elseif ($_GET['type'] == 'medecins') {
        echo json_encode(getStatistiquesMedecins());
    } else {
        echo json_encode(['error' => 'Type de statistique non reconnu.']);
    }
} else {
    echo json_encode(['error' => 'Veuillez spécifier un type de statistique.']);
}
