<?php
include 'BD.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM usager WHERE ID_USAGER = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Usager supprimé avec succès.";
        } else {
            echo "Aucun usager trouvé avec cet ID, ou l'usager n'a pas pu être supprimé.";
        }
        
        // Redirection vers la page de liste des usagers
        header('Location: afficher_usagers.php');
    } catch (PDOException $e) {
        die("Erreur lors de la suppression de l'usager : " . $e->getMessage());
    }
} else {
    echo "ID d'usager non spécifié.";
}
?>
