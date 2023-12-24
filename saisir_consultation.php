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
    <title>Saisie de Consultation</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <?php include 'menu.php'; ?>
    <?php include 'html/formulaire_saisir_consultation.html'; ?>
</body>

</html>