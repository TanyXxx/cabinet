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
    <title>Accueil - Gestion Cabinet Médical</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <h1>Bienvenue dans le système de gestion du cabinet médical</h1>
    <!-- Autres contenus -->
</body>
</html>
