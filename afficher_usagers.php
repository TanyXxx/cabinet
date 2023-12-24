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
    <title>Afficher Usagers</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Assurez-vous que le chemin vers le fichier CSS est correct -->
</head>

<body>
    <?php include 'menu.php'; ?>
    <?php include 'php/liste_usagers.php'; ?>
</body>

</html>