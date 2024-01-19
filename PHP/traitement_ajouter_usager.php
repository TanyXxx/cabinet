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
    $medecin_ref = $_POST['medecin_ref'];
    try {
        $sql = "INSERT INTO usager (Civilite, Nom, Prenom, Adresse, Lieu_Naissance, Date_Naissance, Numero_Secu, ID_Medecin_Ref) 
                VALUES (:civilite, :nom, :prenom, :adresse, :lieu_naissance, :date_naissance, :numero_secu, :medecin_ref)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':lieu_naissance', $lieu_naissance, PDO::PARAM_STR);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':numero_secu', $numero_secu, PDO::PARAM_STR);
        $stmt->bindParam(':medecin_ref', $medecin_ref, PDO::PARAM_INT);

        $stmt->execute();
        echo "Usager ajouté avec succès.";

        // Redirection vers afficher_usagers.php
        header("Location: ../afficher_usagers.php");
        exit(); // Assurez-vous d'ajouter exit() après la redirection pour terminer l'exécution du script.
        
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
