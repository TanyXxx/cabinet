<?php
require_once 'StatistiquesFunctions.php';
require_once '../authapi/jwt_utils.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$jwt = get_bearer_token();

if (!$jwt || !is_jwt_valid($jwt, 'your_secret_key')) {
    deliver_response(401, "Accès refusé, veuillez vous reconnectez", NULL);
    exit();
} 
if (isset($_GET['type'])) {
    if ($_GET['type'] == 'usagers') {
        echo getStatistiquesUsagers();
    } elseif ($_GET['type'] == 'medecins') {
        echo getStatistiquesMedecins();
    } else {
        echo json_encode(['error' => 'Type de statistique non reconnu.']);
    }
} else {
    echo json_encode(['error' => 'Veuillez spécifier un type de statistique.']);
}