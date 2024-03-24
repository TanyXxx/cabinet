<?php
require_once 'MedecinFunctions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');
header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);

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
