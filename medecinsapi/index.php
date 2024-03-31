<?php
require_once 'MedecinFunctions.php';
require_once '../authapi/jwt_utils.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'OPTIONS') {
    deliver_response(200, "CORS preflight response OK");
    exit();
}

$id = $_GET['id'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
$jwt = get_bearer_token();

if (!$jwt || !is_jwt_valid($jwt, 'your_secret_key')) {
    deliver_response(401, "Accès refusé, veuillez vous reconnectez", NULL);
    exit();
}
switch ($requestMethod) {
    case 'GET':
        if ($id) {
            getMedecinById($id);
        } else {
            getAllMedecins();
        }
        break;
    case 'POST':
        addMedecin($input);
        break;
    case 'PATCH':
        if ($id && !empty($input)) {
            updateMedecin($id, $input);
        } else {
            deliver_response(400, "ID du médecin et données requises pour la mise à jour.");
        }
        break;
    case 'DELETE':
        if ($id) {
            deleteMedecin($id);
        } else {
            deliver_response(400, "ID du médecin requis pour la suppression.");
        }
        break;
    default:
        deliver_response(405, 'Method Not Allowed');
        break;
}
