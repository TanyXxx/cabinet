<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $civilite = $_POST['civilite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $date_naissance = $_POST['date_naissance'];
    $numero_secu = $_POST['numero_secu'];

    try {
        $sql = "INSERT INTO usager (Civilité, Nom, Prénom, Adresse, Ville, Code_Postal, Date_Naissance, Numero_Secu) 
                VALUES (:civilite, :nom, :prenom, :adresse, :ville, :code_postal, :date_naissance, :numero_secu)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
        $stmt->bindParam(':code_postal', $code_postal, PDO::PARAM_STR);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':numero_secu', $numero_secu, PDO::PARAM_INT);

        $stmt->execute();
        echo "Usager ajouté avec succès.";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<form method="post" action="ajouter_usager.php">
    Civilité: <input type="text" name="civilite">
    Nom: <input type="text" name="nom">
    Prénom: <input type="text" name="prenom">
    Adresse: <input type="text" name="adresse">
    Ville: <input type="text" name="ville">
    Code Postal: <input type="text" name="code_postal">
    Date de Naissance: <input type="date" name="date_naissance">
    Numéro de Sécurité Sociale: <input type="number" name="numero_secu">
    <input type="submit" value="Ajouter">
</form>