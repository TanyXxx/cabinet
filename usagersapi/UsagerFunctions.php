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

function getAllUsagers()
{
    global $conn;
    $sql = "SELECT usager.*, CONCAT(medecin.Nom, ' ', medecin.Prenom) AS MedecinReferent
            FROM usager
            LEFT JOIN medecin ON usager.ID_Medecin_Ref = medecin.ID_Medecin";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $usagers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($usagers as $key => $usager) {
            unset($usagers[$key]['ID_Medecin_Ref']); 
            if (empty($usager['MedecinReferent'])) {
                $usagers[$key]['MedecinReferent'] = 'Non assigné'; 
            }
        }

        deliver_response(200, "Usagers récupérés avec succès", $usagers);
    } catch (PDOException $e) {
        deliver_response(500, "Erreur interne du serveur: " . $e->getMessage());
    }
}


function getUsagerById($id)
{
    global $conn;
    $sql = "SELECT usager.*, medecin.Nom as MedecinNom, medecin.Prenom as MedecinPrenom 
            FROM usager 
            LEFT JOIN medecin ON usager.ID_Medecin_Ref = medecin.ID_Medecin 
            WHERE usager.ID_USAGER = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usager = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usager) {
            $medecinReferent = trim($usager['MedecinNom'] . ' ' . $usager['MedecinPrenom']);
            $usager['MedecinReferent'] = $medecinReferent ? $medecinReferent : 'Non assigné';
            unset($usager['MedecinNom'], $usager['MedecinPrenom']);

            deliver_response(200, "Usager trouvé", $usager);
        } else {
            deliver_response(404, "Aucun usager trouvé avec cet ID");
        }
    } catch (PDOException $e) {
        deliver_response(500, "Erreur interne du serveur: " . $e->getMessage());
    }
}


function getPrenomNomFromIDMedecinRef($idMedecinRef)
{
    global $conn;
    $sql = "SELECT CONCAT(Civilite, ' ', Nom, ' ', Prenom) AS MedecinReferent FROM medecin WHERE ID_Medecin = :idMedecinRef";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idMedecinRef', $idMedecinRef, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['MedecinReferent'];
        } else {
            return null; // ou renvoyer une valeur par défaut, par exemple 'Non assigné'
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du médecin référent: " . $e->getMessage());
        return null;
    }
}

function addUsager($data) {
    global $conn;

    // Traitement de la date de naissance
    $formatted_date_nais = null;
    if (!empty($data['date_nais'])) {
        $date_nais = DateTime::createFromFormat('d/m/Y', $data['date_nais']);
        if ($date_nais !== false) {
            $formatted_date_nais = $date_nais->format('Y-m-d');
        }
    }

    // Utilisation de isset pour définir une valeur par défaut si la clé n'existe pas
    $civilite = isset($data['civilite']) ? $data['civilite'] : null;
    $nom = isset($data['nom']) ? $data['nom'] : null;
    $prenom = isset($data['prenom']) ? $data['prenom'] : null;
    $sexe = isset($data['sexe']) ? $data['sexe'] : null;
    $adresse = isset($data['adresse']) ? $data['adresse'] : null;
    $code_postal = isset($data['code_postal']) ? $data['code_postal'] : null;
    $ville = isset($data['ville']) ? $data['ville'] : null;
    $lieu_nais = isset($data['lieu_nais']) ? $data['lieu_nais'] : null;
    $num_secu = isset($data['num_secu']) ? $data['num_secu'] : null;
    $id_medecin_ref = isset($data['id_medecin']) ? $data['id_medecin'] : null;


    // Préparation de la requête SQL
    $sql = "INSERT INTO usager (
                Civilite, Nom, Prenom, Sexe, Adresse, Code_Postal,
                Ville, Date_Naissance, Lieu_Naissance, Numero_Secu, ID_Medecin_Ref
            ) VALUES (
                :civilite, :nom, :prenom, :sexe, :adresse, :code_postal,
                :ville, :date_nais, :lieu_nais, :num_secu, :id_medecin_ref
            )";

    // Préparation des paramètres pour la requête SQL
    $parameters = [
        ':civilite' => $civilite,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':sexe' => $sexe,
        ':adresse' => $adresse,
        ':code_postal' => $code_postal,
        ':ville' => $ville,
        ':date_nais' => $formatted_date_nais,
        ':lieu_nais' => $lieu_nais,
        ':num_secu' => $num_secu,
        ':id_medecin_ref' => $id_medecin_ref
    ];

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($parameters);
        deliver_response(201, "Usager créé avec succès.");
    } catch (PDOException $e) {
        deliver_response(500, "Erreur lors de la création de l'usager: " . $e->getMessage());
    }
}


function updateUsager($id, $data)
{
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



function deleteUsager($id)
{
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
