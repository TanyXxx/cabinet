<?php
require_once 'StatistiquesFunctions.php';
require_once '../authapi/jwt_utils.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    deliver_response(200, "CORS preflight response OK");
    exit();
}

$jwt = get_bearer_token();

if (!$jwt || !is_jwt_valid($jwt, 'your_secret_key')) {
    deliver_response(401, "Accès refusé, veuillez vous reconnecter", NULL);
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