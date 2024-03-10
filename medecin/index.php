<?php
require_once 'MedecinFunctions.php';

// Handle CORS requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');
header('Content-Type: application/json');

// Collect request data
$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);

switch ($requestMethod) {
    case 'GET':
        if (isset($_GET['id'])) {
            $response = getMedecinById($_GET['id']);
        } else {
            $response = getAllMedecins();
        }
        break;
    case 'POST':
        $response = addMedecin($input['civilite'], $input['nom'], $input['prenom']);
        break;
    case 'PATCH':
        if ($id) {
            $response = updateMedecin(
                $id,
                $input['civilite'] ?? null,
                $input['nom'] ?? null,
                $input['prenom'] ?? null
            );
        } else {
            $response = "ID du médecin est requis pour PATCH.";
        }
        break;
    case 'DELETE':
        if ($id) {
            $response = deleteMedecin($id);
        } else {
            $response = "ID du médecin est requis pour DELETE.";
        }
        break;
    // Gérer les autres cas 
    default:
        http_response_code(405);
        $response = ['error' => 'Method Not Allowed'];
        break;
}

echo json_encode($response);
