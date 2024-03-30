<?php
require_once '../BD/connexionDB.php'; 
require_once '../authapi/jwt_utils.php';

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

function addConsultation($data) {
    global $conn;
    $sql = "INSERT INTO consultation (ID_USAGER, ID_Medecin, Date_Consultation, Heure, Duree) VALUES (:id_usager, :id_medecin, :date_consult, :heure_consult, :duree_consult)";

    try {
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([
            ':id_usager' => $data['id_usager'],
            ':id_medecin' => $data['id_medecin'],
            ':date_consult' => $data['date_consult'],
            ':heure_consult' => $data['heure_consult'],
            ':duree_consult' => $data['duree_consult']
        ]);

        if ($success) {
            deliver_response(201, "Consultation créée avec succès", ['id' => $conn->lastInsertId()]);
        } else {
            deliver_response(400, "Erreur lors de la création de la consultation");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur : " . $e->getMessage());
    }
}


function getAllConsultations() {
    global $conn;
    $sql = "SELECT consultation.*, medecin.Nom as MedecinNom, medecin.Prenom as MedecinPrenom, usager.Nom as UsagerNom, usager.Prenom as UsagerPrenom
            FROM consultation
            LEFT JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin
            LEFT JOIN usager ON consultation.ID_USAGER = usager.ID_USAGER";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $consultations = array_map(function($consultation) {
            $medecin = trim($consultation['MedecinNom'] . ' ' . $consultation['MedecinPrenom']);
            $usager = trim($consultation['UsagerNom'] . ' ' . $consultation['UsagerPrenom']);
            $consultation['Medecin'] = $medecin ? $medecin : 'Non assigné';
            $consultation['Usager'] = $usager ? $usager : 'Non assigné';
            unset($consultation['MedecinNom'], $consultation['MedecinPrenom'], $consultation['UsagerNom'], $consultation['UsagerPrenom']);
            return $consultation;
        }, $consultations);

        deliver_response(200, "OK", $consultations);
    } catch (PDOException $e) {
        deliver_response(500, "Erreur : " . $e->getMessage());
    }
}

function getConsultationById($id) {
    global $conn;
    $sql = "SELECT consultation.*, medecin.Nom as MedecinNom, medecin.Prenom as MedecinPrenom, usager.Nom as UsagerNom, usager.Prenom as UsagerPrenom
            FROM consultation
            LEFT JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin
            LEFT JOIN usager ON consultation.ID_USAGER = usager.ID_USAGER
            WHERE consultation.ID_Consultation = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $consultation = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($consultation) {
            $medecin = trim($consultation['MedecinNom'] . ' ' . $consultation['MedecinPrenom']);
            $usager = trim($consultation['UsagerNom'] . ' ' . $consultation['UsagerPrenom']);
            $consultation['Medecin'] = $medecin ? $medecin : 'Non assigné';
            $consultation['Usager'] = $usager ? $usager : 'Non assigné';
            unset($consultation['MedecinNom'], $consultation['MedecinPrenom'], $consultation['UsagerNom'], $consultation['UsagerPrenom']);

            deliver_response(200, "Consultation trouvée", $consultation);
        } else {
            deliver_response(404, "Aucune consultation trouvée avec l'ID spécifié.");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur : " . $e->getMessage());
    }
}

function getConsultationsByMedecinId($id_medecin) {
    global $conn;
    $sql = "SELECT consultation.*, medecin.Nom as MedecinNom, medecin.Prenom as MedecinPrenom, usager.Nom as UsagerNom, usager.Prenom as UsagerPrenom
            FROM consultation
            LEFT JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin
            LEFT JOIN usager ON consultation.ID_USAGER = usager.ID_USAGER
            WHERE consultation.ID_Medecin = :id_medecin";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_medecin', $id_medecin, PDO::PARAM_INT);
        $stmt->execute();
        $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $consultations = array_map(function($consultation) {
            $medecin = trim($consultation['MedecinNom'] . ' ' . $consultation['MedecinPrenom']);
            $usager = trim($consultation['UsagerNom'] . ' ' . $consultation['UsagerPrenom']);
            $consultation['Medecin'] = $medecin ? $medecin : 'Non assigné';
            $consultation['Usager'] = $usager ? $usager : 'Non assigné';
            unset($consultation['MedecinNom'], $consultation['MedecinPrenom'], $consultation['UsagerNom'], $consultation['UsagerPrenom']);
            return $consultation;
        }, $consultations);

        deliver_response(200, "OK", $consultations);
    } catch (PDOException $e) {
        deliver_response(500, "Erreur : " . $e->getMessage());
    }
}

function updateConsultation($id, $data) {
    global $conn;
    $columnMappings = [
        'id_usager' => 'ID_USAGER',
        'id_medecin' => 'ID_Medecin',
        'date_consult' => 'Date_Consultation',
        'heure_consult' => 'Heure',
        'duree_consult' => 'Duree'
    ];

    $existSql = "SELECT 1 FROM consultation WHERE ID_Consultation = :id";
    $existStmt = $conn->prepare($existSql);
    $existStmt->execute([':id' => $id]);
    if ($existStmt->fetchColumn() === false) {
        deliver_response(404, "Consultation non trouvée avec l'ID spécifié.");
        return;
    }

    $sql = "UPDATE consultation SET ";
    $params = [];
    foreach ($data as $key => $value) {
        if (isset($columnMappings[$key])) {
            $dbColumn = $columnMappings[$key];
            $sql .= "$dbColumn = :$key, ";
            $params[":$key"] = $value;
        }
    }
    $sql = rtrim($sql, ', ');
    $sql .= " WHERE ID_Consultation = :id";
    $params[':id'] = $id;

    try {
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        $affectedRows = $stmt->rowCount();

        if ($success && $affectedRows > 0) {
            deliver_response(200, "Consultation mise à jour avec succès.");
        } elseif ($success && $affectedRows === 0) {
            deliver_response(400, "Aucune modification apportée. Les données fournies sont peut-être identiques aux données existantes.");
        } else {
            deliver_response(500, "Erreur lors de la mise à jour de la consultation.");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur lors de la mise à jour de la consultation: " . $e->getMessage());
    }
}


function deleteConsultation($id) {
    global $conn;
    $sql = "DELETE FROM consultation WHERE ID_Consultation = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            deliver_response(200, "Consultation supprimée avec succès.");
        } else {
            deliver_response(404, "Aucune consultation trouvée avec l'ID spécifié.");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur : " . $e->getMessage());
    }
}
