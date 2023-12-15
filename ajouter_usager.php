<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'BD.php';

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    // Ajoutez d'autres champs selon votre base de données

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $sql = "INSERT INTO usagers (nom, prenom, ...) VALUES (:nom, :prenom, ...)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        // Liez d'autres paramètres

        $stmt->execute();
        echo "Usager ajouté avec succès.";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<form method="post" action="ajouter_usager.php">
    Nom: <input type="text" name="nom">
    Prénom: <input type="text" name="prenom">
    <!-- Ajoutez d'autres champs de formulaire ici -->
    <input type="submit" value="Ajouter">
</form>