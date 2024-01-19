<?php include 'php/session.php'?>


<!DOCTYPE html>
<html>

<head>
    <title>Supprimer Usager</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css"> <!-- Assurez-vous que le chemin est correct -->
</head>

<body>
    <?php include 'menu.php'; ?>
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        echo "<h2>Confirmez-vous la suppression de l'usager ID: " . htmlspecialchars($id) . " ?</h2>";
        echo "<a href='/cabinet/PHP/traitement_supprimer_usager.php?id=" . $id . "'>Confirmer la Suppression</a>";
    } else {
        echo "<p>ID d'usager non spécifié.</p>";
    }
    ?>
</body>

</html>