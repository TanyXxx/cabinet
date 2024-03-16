<?php
require_once 'ConsultationFunctions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');
header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null; 
$input = json_decode(file_get_contents('php://input'), true); 

switch ($requestMethod) {
    case 'GET':
        if ($id !== null) {
            getConsultationById($id); 
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
        }
        else {
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


