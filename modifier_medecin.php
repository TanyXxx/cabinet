<?php include 'PHP/session.php'?>

<?php
include 'PHP/BD.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "SELECT * FROM medecin WHERE ID_Medecin = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $medecin = $stmt->fetch();
        } else {
            echo "Médecin non trouvé.";
            exit;
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "ID de médecin non spécifié.";
    exit;
} 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modification de Médecin</title>
    <link rel="stylesheet" type="text/css" href="CSS/ajouter.css">
</head>
<body>
    <?php if (isset($medecin)): ?>
        <?php include 'menu.php'; ?>
        <?php include 'HTML/formulaire_modifier_medecin.html'; ?>
    <?php endif; ?>
</body>
</html>
