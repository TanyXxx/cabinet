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
    <title>Ajout d'Usager</title>
    <link rel="stylesheet" type="text/css" href="css/ajouter_usager.css">
</head>

<body>
    <?php include 'menu.php'; ?>
    <?php include 'php/traitement_ajouter_usager.php'; ?>
    <?php include 'html/formulaire_ajouter_usager.html'; ?>
</body>

</html>