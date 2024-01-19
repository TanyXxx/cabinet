<?php include 'php/session.php'?>
<?php
include 'php/BD.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "SELECT * FROM consultation WHERE ID_Consultation = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $consultation = $stmt->fetch();
        } else {
            echo "Consultation non trouvée.";
            exit;
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "ID de consultation non spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modification de Consultation</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <?php include 'html/formulaire_modifier_consultation.html'; ?>
</body>
</html>
