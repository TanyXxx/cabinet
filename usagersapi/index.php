<?php
require_once 'UsagerFunctions.php';
require_once '../authapi/jwt_utils.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');
header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
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
                getUsagerById($id);
            } else {
                getAllUsagers();
            }
            break;
        case 'POST':
            addUsager($input);
            break;
        case 'PATCH':
            if ($id && !empty($input)) {
                updateUsager($id, $input);
            }
            break;
        case 'DELETE':
            if ($id) {
                deleteUsager($id);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
            break;
    }
