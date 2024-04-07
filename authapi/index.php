<?php
require_once 'connexionDBauth.php';
require_once 'jwt_utils.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'OPTIONS':
        deliver_response(200, "OK");
        break;
    case 'GET':
        $jwt = get_bearer_token();

        if ($jwt && is_jwt_valid($jwt, 'secret_key')) {
            $payload = json_decode(base64url_decode(explode('.', $jwt)[1]), true);
            deliver_response(200, 'Accès autorisé. Le jeton JWT est valide.', ['payload' => $payload]);
        } else {
            deliver_response(401, 'Accès non autorisé. Jeton invalide ou manquant.');
        }
        break;
        
    case 'POST':
        // Récupération du login et mot de passe depuis les données reçues
        $input = json_decode(file_get_contents('php://input'), true);
        $login = $input['login'] ?? '';
        $password = $input['mdp'] ?? '';

        // Vérifier l'utilisateur dans la base de données
        $user = verify_user($login, $password);

        if ($user) {
            // L'utilisateur est valide, générer et renvoyer un JWT
            $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
            $payload = [
                'login' => $user['NomUtilisateur'], 
                'role' => $user['Role'], 
                'exp' => (time() + 60 * 60 * 24) // Expiration après 24 heure
            ];             
            $jwt = generate_jwt($headers, $payload, 'secret_key'); 

            deliver_response(200, 'JWT généré avec succès.', ['jwt' => $jwt]);
        } else {
            deliver_response(401, 'Login ou mot de passe incorrect.');
        }
        break;

    default:
        deliver_response(405, 'Méthode non autorisée.');
        break;
}

