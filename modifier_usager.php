<?php
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    // Ajoutez d'autres champs selon votre base de données

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $sql = "UPDATE usagers SET nom = :nom, prenom = :prenom, ... WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        // Liez d'autres paramètres

        $stmt->execute();
        echo "Usager modifié avec succès.";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $sql = "SELECT * FROM usagers WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            // Pré-remplir le formulaire avec les données de l'usager
            ?>
            <form method="post" action="modifier_usager.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                Nom: <input type="text" name="nom" value="<?php echo $row['nom']; ?>">
                Prénom: <input type="text" name="prenom" value="<?php echo $row['prenom']; ?>">
                <!-- Ajoutez d'autres champs de formulaire ici -->
                <input type="submit" value="Modifier">
            </form>
            <?php
        } else {
            echo "Usager non trouvé.";
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>