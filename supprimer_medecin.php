<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supprimer Médecin</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        echo "<h2>Confirmez-vous la suppression du médecin ID: " . htmlspecialchars($id) . " ?</h2>";
        echo "<a href='/cabinet/cabinet-1/php/traitement_supprimer_medecin.php?id=" . $id . "'>Confirmer la Suppression</a>";
    } else {
        echo "<p>ID de médecin non spécifié.</p>";
    }
    ?>
</body>
</html>
