<?php
require_once '../BD/connexionDB.php';

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

function getAllMedecins()
{
    global $conn;
    $sql = "SELECT * FROM medecin";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $medecins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        deliver_response(200, "Médecins récupérés avec succès", $medecins);
    } catch (PDOException $e) {
        deliver_response(500, "Erreur interne du serveur: " . $e->getMessage());
    }
}

function getMedecinById($id)
{
    global $conn;
    $sql = "SELECT * FROM medecin WHERE ID_Medecin = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $medecin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($medecin) {
            deliver_response(200, "Médecin trouvé", $medecin);
        } else {
            deliver_response(404, "Aucun médecin trouvé avec cet ID");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur interne du serveur: " . $e->getMessage());
    }
}

function addMedecin($input)
{
    global $conn;
    if (empty($input['civilite']) || empty($input['nom']) || empty($input['prenom'])) {
        deliver_response(400, "Les champs 'civilite', 'nom', et 'prenom' sont requis.");
        return;
    }

    $sql = "INSERT INTO medecin (Civilite, Nom, Prenom) VALUES (:civilite, :nom, :prenom)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':civilite', $input['civilite']);
        $stmt->bindParam(':nom', $input['nom']);
        $stmt->bindParam(':prenom', $input['prenom']);
        $stmt->execute();
        // Récupérer les données du médecin après l'ajout
        $existSql = "SELECT * FROM medecin WHERE ID_Medecin = :id";
        $existStmt = $conn->prepare($existSql);
        $existStmt->execute([':id' => $conn->lastInsertId()]);
        $medecin = $existStmt->fetch(PDO::FETCH_ASSOC);

        deliver_response(201, "Médecin ajouté avec succès.", $medecin);
    } catch (PDOException $e) {
        deliver_response(500, "Erreur interne du serveur: " . $e->getMessage());
    }
}

function updateMedecin($id, $input)
{
    global $conn;
    $sets = [];
    $params = [':id' => $id];
    foreach ($input as $key => $value) {
        $sets[] = "$key = :$key";
        $params[":$key"] = $value;
    }
    if (empty($sets)) {
        deliver_response(400, "Aucune donnée fournie pour la mise à jour.");
        return;
    }

    $sql = "UPDATE medecin SET " . implode(', ', $sets) . " WHERE ID_Medecin = :id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        // Récupérer les données du médecin après la mise à jour
        $existSql = "SELECT * FROM medecin WHERE ID_Medecin = :id";
        $existStmt = $conn->prepare($existSql);
        $existStmt->execute([':id' => $id]);
        $medecin = $existStmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            deliver_response(200, "Médecin modifié avec succès.", $medecin);
        } else {
            $existCheckStmt = $conn->prepare("SELECT 1 FROM medecin WHERE ID_Medecin = :id");
            $existCheckStmt->execute([':id' => $id]);
            if ($existCheckStmt->fetch()) {
                deliver_response(400, "Mise à jour non effectuée, les données correspondent déjà aux valeurs fournies.", $medecin);
            } else {
                deliver_response(404, "Aucun médecin trouvé avec l'ID spécifié.", ['id' => $id]);
            }
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur lors de la mise à jour du médecin: " . $e->getMessage());
    }
}

function deleteMedecin($id)
{
    global $conn;
    // Récupérer les données du médecin avant de le supprimer
    $sql = "SELECT * FROM medecin WHERE ID_Medecin = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $medecin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($medecin === false) {
            deliver_response(404, "Aucun médecin trouvé avec l'ID spécifié.", ['id' => $id]);
            return;
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur : " . $e->getMessage(), ['id' => $id]);
        return;
    }

    // Supprimer le médecin
    $sql = "DELETE FROM medecin WHERE ID_Medecin = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            deliver_response(200, "Médecin supprimé avec succès.", $medecin);
        } else {
            deliver_response(404, "Aucun médecin trouvé avec l'ID spécifié.", ['id' => $id]);
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur, le medecin est lié à une consultation: " . $e->getMessage(), ['id' => $id]);
    }
}

