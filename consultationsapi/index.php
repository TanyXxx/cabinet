<?php
require_once 'ConsultationFunctions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');


$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'OPTIONS') {
    deliver_response(200, "CORS preflight response OK");
    exit();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$id_medecin = isset($_GET['id_medecin']) ? (int) $_GET['id_medecin'] : null;
$input = json_decode(file_get_contents('php://input'), true);
$jwt = get_bearer_token();

if (!$jwt || !is_jwt_valid($jwt, 'your_secret_key')) {
    deliver_response(401, "Accès refusé, veuillez vous reconnectez", NULL);
    exit();
}

switch ($requestMethod) {
    case 'GET':
        if ($id !== null) {
            getConsultationById($id);
        } elseif ($id_medecin !== null) {
            getConsultationsByMedecinId($id_medecin);
        } else {
            getAllConsultations();
        }
        break;
    case 'POST':
        addConsultation($input);
        break;
    case 'PATCH':
        if ($id !== null) {
            updateConsultation($id, $input);
        } else {
            deliver_response(400, "ID de la consultation est requis pour PATCH.");
        }
        break;
    case 'DELETE':
        if ($id !== null) {
            deleteConsultation($id);
        } else {
            deliver_response(400, "ID de la consultation est requis pour DELETE.");
        }
        break;
    default:
        deliver_response(405, 'Method Not Allowed');
        break;
}
