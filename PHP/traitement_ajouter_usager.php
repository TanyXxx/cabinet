<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $civilite = $_POST['civilite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $lieu_naissance = $_POST['lieu_naissance'];
    $date_naissance = $_POST['date_naissance'];
    $numero_secu = $_POST['numero_secu'];

    try {
        $sql = "INSERT INTO usager (Civilite, Nom, Prenom, Adresse, Lieu_Naissance, Date_Naissance, Numero_Secu) 
                VALUES (:civilite, :nom, :prenom, :adresse, :lieu_naissance, :date_naissance, :numero_secu)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':lieu_naissance', $lieu_naissance, PDO::PARAM_STR);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':numero_secu', $numero_secu, PDO::PARAM_STR);

        $stmt->execute();
        echo "Usager ajouté avec succès.";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
