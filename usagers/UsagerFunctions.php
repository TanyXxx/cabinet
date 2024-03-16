<?php
require_once '../connexionDB.php';

function deliver_response($status_code, $status_message, $data = null)
{
    http_response_code($status_code);
    header("Content-Type:application/json; charset=utf-8");

    $status_phrases = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    $response = [
        'status_code' => $status_code,
        'status' => isset($status_phrases[$status_code]) ? $status_phrases[$status_code] : 'Unknown Status',
        'status_message' => $status_message,
        'data' => $data,
    ];

    $json_response = json_encode($response, JSON_UNESCAPED_UNICODE);
    if ($json_response === false) {
        die('json encode ERROR : ' . json_last_error_msg());
    }

    echo $json_response;
}

function getAllUsagers() {
    global $conn;
    $sql = "SELECT * FROM usager";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $usagers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        deliver_response(200, "Usagers récupérés avec succès", $usagers);
    } catch (PDOException $e) {
        deliver_response(500, "Erreur interne du serveur: " . $e->getMessage());
    }
}

function getUsagerById($id) {
    global $conn;
    $sql = "SELECT * FROM usager WHERE ID_USAGER = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usager = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usager) {
            deliver_response(200, "Usager trouvé", $usager);
        } else {
            deliver_response(404, "Aucun usager trouvé avec cet ID");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur interne du serveur: " . $e->getMessage());
    }
}

function addUsager($data) {
    global $conn;
    $data[':id_medecin_ref'] = $data['id_medecin'];
    unset($data['id_medecin']); 

    $sql = "INSERT INTO usager (Civilite, Nom, Prenom, Sexe, Adresse, Code_Postal, Ville, Date_Naissance, Lieu_Naissance, Numero_Secu, ID_Medecin_Ref) VALUES (:civilite, :nom, :prenom, :sexe, :adresse, :code_postal, :ville, :date_nais, :lieu_nais, :num_secu, :id_medecin_ref)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        deliver_response(201, "Usager créé avec succès.");
    } catch (PDOException $e) {
        deliver_response(500, "Erreur lors de la création de l'usager: " . $e->getMessage());
    }
}


function updateUsager($id, $data) {
    global $conn;

    $existCheckSql = "SELECT 1 FROM usager WHERE ID_USAGER = :id";
    $existCheckStmt = $conn->prepare($existCheckSql);
    $existCheckStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $existCheckStmt->execute();
    if ($existCheckStmt->rowCount() === 0) {
        deliver_response(404, "Aucun usager trouvé avec l'ID spécifié.");
        return;
    }

    $sets = [];
    $params = [':id' => $id];
    foreach ($data as $key => $value) {
        if ($key === 'id_medecin') {
            $key = 'ID_Medecin_Ref';
        }
        $sets[] = "$key = :$key";
        $params[":$key"] = $value;
    }

    if (empty($sets)) {
        deliver_response(400, "Aucune donnée fournie pour la mise à jour.");
        return;
    }

    $sql = "UPDATE usager SET " . implode(', ', $sets) . " WHERE ID_USAGER = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        if ($stmt->rowCount() > 0) {
            deliver_response(200, "Usager modifié avec succès.");
        } else {
            deliver_response(400, "Aucune modification effectuée, les données fournies correspondent déjà aux valeurs existantes.");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur lors de la mise à jour de l'usager: " . $e->getMessage());
    }
}



function deleteUsager($id) {
    global $conn;
    $sql = "DELETE FROM usager WHERE ID_USAGER = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            deliver_response(200, "Usager supprimé avec succès.");
        } else {
            deliver_response(404, "Aucun usager trouvé avec cet ID.");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur lors de la suppression de l'usager: " . $e->getMessage());
    }
}