<?php
require_once '../connexionDB.php'; 

function addConsultation($data) {
    global $conn;
    $sql = "INSERT INTO consultation (ID_USAGER, ID_Medecin, Date_Consultation, Heure, Duree) VALUES (:id_usager, :id_medecin, :date_consult, :heure_consult, :duree_consult)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_usager' => $data['id_usager'],
            ':id_medecin' => $data['id_medecin'],
            ':date_consult' => $data['date_consult'],
            ':heure_consult' => $data['heure_consult'],
            ':duree_consult' => $data['duree_consult']
        ]);
        return "Consultation créée avec succès.";
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function getAllConsultations() {
    global $conn;
    $sql = "SELECT * FROM consultation";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function getConsultationById($id) {
    global $conn;
    $sql = "SELECT * FROM consultation WHERE ID_Consultation = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function updateConsultation($id, $data) {
    global $conn;
    // Map the request fields to the actual database columns
    $columnMappings = [
        'id_usager' => 'ID_USAGER',
        'id_medecin' => 'ID_Medecin',
        'date_consult' => 'Date_Consultation',
        'heure_consult' => 'Heure',
        'duree_consult' => 'Duree'
    ];

    $sql = "UPDATE consultation SET ";
    $params = [];
    foreach ($data as $key => $value) {
        if (isset($columnMappings[$key])) {
            $dbColumn = $columnMappings[$key];
            $sql .= "$dbColumn = :$dbColumn, ";
            $params[":$dbColumn"] = $value;
        }
    }
    $sql = rtrim($sql, ', ');
    $sql .= " WHERE ID_Consultation = :id";
    $params[':id'] = $id;

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        if ($stmt->rowCount() > 0) {
            return "Consultation mise à jour avec succès.";
        } else {
            // This condition is specifically for when no rows are affected
            return "Aucune mise à jour effectuée. Vérifiez que l'ID est correct et que les données diffèrent des valeurs actuelles.";
        }
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}




function deleteConsultation($id) {
    global $conn;
    $sql = "DELETE FROM consultation WHERE ID_Consultation = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return "Consultation supprimée avec succès.";
            } else {
                return "Aucune consultation trouvée avec l'ID spécifié.";
            }
        } else {
            return "Erreur lors de la tentative de suppression.";
        }
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}
?>
