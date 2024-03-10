<?php
require_once 'UsagerFunctions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');
header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null; // For GET, PATCH, DELETE to specify which usager
$input = json_decode(file_get_contents('php://input'), true); // For POST and PATCH

switch ($requestMethod) {
    case 'GET':
        if ($id) {
            $response = getUsagerById($id);
        } else {
            $response = getAllUsagers();
        }
        break;
    case 'POST':
        $response = addUsager($input);
        break;
    case 'PATCH':
        if ($id) {
            $input['id'] = $id; 
            $response = updateUsager($id, $input);
        } else {
            $response = "ID de l'usager est requis pour PATCH.";
        }
        break;
    case 'DELETE':
        if ($id) {
            $response = deleteUsager($id);
        } else {
            $response = "ID de l'usager est requis pour DELETE.";
        }
        break;
    default:
        http_response_code(405);
        $response = 'Method Not Allowed';
        break;
}

echo json_encode($response);
?>
