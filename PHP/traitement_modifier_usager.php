<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $civilite = $_POST['civilite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $lieuNaissance = $_POST['lieu_naissance'];
    $dateNaissance = $_POST['date_naissance'];
    $numeroSecu = $_POST['numero_secu'];

    try {
        $sql = "UPDATE usager SET Civilite = :civilite, Nom = :nom, Prenom = :prenom, Adresse = :adresse, 
                Lieu_Naissance = :lieuNaissance, Date_Naissance = :dateNaissance, Numero_Secu = :numeroSecu 
                WHERE ID_USAGER = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':lieuNaissance', $lieuNaissance, PDO::PARAM_STR);
        $stmt->bindParam(':dateNaissance', $dateNaissance);
        $stmt->bindParam(':numeroSecu', $numeroSecu, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());
        } else {
            echo "Usager modifié avec succès.";
        }
    } catch (PDOException $e) {
        die("Erreur lors de la modification de l'usager : " . $e->getMessage());
    }
}
?>
