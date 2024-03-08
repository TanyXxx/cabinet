<?php
require_once 'connexionDB.php';
require_once 'jwt_utils.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $jwt = get_bearer_token();

        if ($jwt && is_jwt_valid($jwt, 'your_secret_key')) {
            $payload = json_decode(base64url_decode(explode('.', $jwt)[1]), true);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Accès non autorisé. Jeton invalide ou manquant.']);
        }
        break;
        
    case 'POST':
        // Récupération du login et mot de passe depuis les données reçues
        $input = json_decode(file_get_contents('php://input'), true);
        $login = $input['login'] ?? '';
        $password = $input['password'] ?? '';

        // Vérifier l'utilisateur dans la base de données
        $user = verify_user($login, $password);

        if ($user) {
            // L'utilisateur est valide, générer et renvoyer un JWT
            $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
            $payload = [
                'login' => $user['login'],
                'role' => $user['role'],
                'exp' => (time() + 60 * 60) // Expiration après 1 heure
            ]; 
            $jwt = generate_jwt($headers, $payload, 'your_secret_key'); 

            echo json_encode(['jwt' => $jwt]);
        } else {
            http_response_code(401); // Non autorisé
            echo json_encode(['error' => 'Login ou mot de passe incorrect']);
        }
        break;

    default:
        http_response_code(405); // Méthode non autorisée
        echo json_encode(['error' => 'Méthode non autorisée']);
        break;
}

