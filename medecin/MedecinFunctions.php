<?php
require_once '../connexionDB.php';

function getAllMedecins()
{
    global $conn;
    $sql = "SELECT * FROM medecin";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function addMedecin($civilite, $nom, $prenom)
{
    global $conn;
    $sql = "INSERT INTO medecin (Civilite, Nom, Prenom) VALUES (:civilite, :nom, :prenom)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->execute();
        return "Médecin ajouté avec succès.";
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function updateMedecin($id, $civilite, $nom, $prenom)
{
    global $conn;
    $sql = "UPDATE medecin SET ID_Medecin = ID_Medecin";
    $params = array(':id' => $id);

    if ($civilite !== null) {
        $sql .= ", Civilite = :civilite";
        $params[':civilite'] = $civilite;
    }
    if ($nom !== null) {
        $sql .= ", Nom = :nom";
        $params[':nom'] = $nom;
    }
    if ($prenom !== null) {
        $sql .= ", Prenom = :prenom";
        $params[':prenom'] = $prenom;
    }
    $sql .= " WHERE ID_Medecin = :id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return "Médecin modifié avec succès.";
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

function deleteMedecin($id) {
    global $conn;
    $sql = "DELETE FROM medecin WHERE ID_Medecin = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return "Médecin supprimé avec succès.";
            } else {
                return "Aucun médecin trouvé avec l'ID spécifié.";
            }
        } else {
            return "Erreur lors de la tentative de suppression.";
        }
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}

