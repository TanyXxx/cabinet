<?php include 'php/session.php'?>


<?php
include 'php/BD.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "SELECT * FROM usager WHERE ID_USAGER = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usager = $stmt->fetch();
        } else {
            echo "Usager non trouvé.";
            exit;
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "ID d'usager non spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modification d'Usager</title>
    <link rel="stylesheet" type="text/css" href="css/ajouter.css">
</head>
<body>
    <?php if (isset($usager)): ?>
        <?php include 'menu.php'; ?>
        <?php include 'html/formulaire_modifier_usager.html'; ?>
    <?php endif; ?>
</body>
</html>
