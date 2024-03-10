<?php
require_once 'ConsultationFunctions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');
header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null; // For GET, PATCH, DELETE to specify which consultation
$input = json_decode(file_get_contents('php://input'), true); // For POST and PATCH

switch ($requestMethod) {
    case 'GET':
        if ($id !== null) {
            $response = getConsultationById($id);
        } else {
            $response = getAllConsultations();
        }
        break;
    case 'POST':
        $response = addConsultation($input);
        break;
    case 'PATCH':
        if ($id !== null) {
            $response = updateConsultation($id, $input);
        } else {
            $response = json_encode(["error" => "ID de la consultation est requis pour PATCH."]);
        }
        break;
    case 'DELETE':
        if ($id !== null) {
            $response = deleteConsultation($id);
        } else {
            $response = json_encode(["error" => "ID de la consultation est requis pour DELETE."]);
        }
        break;
    default:
        http_response_code(405);
        $response = json_encode(['error' => 'Method Not Allowed']);
        break;
}

echo json_encode($response);
?>
