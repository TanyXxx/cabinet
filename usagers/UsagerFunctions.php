<?php
require_once '../connexionDB.php';

function getAllUsagers()
{
    global $conn;
    $sql = "SELECT * FROM usager";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function getUsagerById($id)
{
    global $conn;
    $sql = "SELECT * FROM usager WHERE ID_USAGER = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function addUsager($data)
{
    global $conn;
    $sql = "INSERT INTO usager (Civilite, Nom, Prenom, Sexe, Adresse, Code_Postal, Ville, Date_Naissance, Lieu_Naissance, Numero_Secu) VALUES (:civilite, :nom, :prenom, :sexe, :adresse, :code_postal, :ville, :date_nais, :lieu_nais, :num_secu)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':civilite' => $data['civilite'],
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':sexe' => $data['sexe'],
            ':adresse' => $data['adresse'],
            ':code_postal' => $data['code_postal'],
            ':ville' => $data['ville'],
            ':date_nais' => $data['date_nais'],
            ':lieu_nais' => $data['lieu_nais'],
            ':num_secu' => $data['num_secu']
        ]);
        return "Usager créé avec succès.";
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function updateUsager($id, $data)
{
    global $conn;
    unset($data['id']);

    $sql = "UPDATE usager SET ";
    $params = [];
    foreach ($data as $key => $value) {
        $sql .= "$key = :$key, ";
        $params[":$key"] = $value;
    }
    $sql = rtrim($sql, ', ');
    $sql .= " WHERE ID_USAGER = :id";
    $params[':id'] = $id;

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        if ($stmt->rowCount() > 0) {
            return "Usager modifié avec succès.";
        } else {
            return "Aucun usager trouvé avec l'ID spécifié ou aucune modification apportée.";
        }
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}


function deleteUsager($id)
{
    global $conn;
    $sql = "DELETE FROM usager WHERE ID_USAGER = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return "Usager supprimé avec succès.";
            } else {
                return "Aucun usager trouvé avec l'ID spécifié.";
            }
        } else {
            return "Erreur lors de la tentative de suppression.";
        }
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}
