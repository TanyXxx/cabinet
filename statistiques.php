<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php include 'php/session.php'?>


<!DOCTYPE html>
<html>

<head>
    <title>Statistiques du Cabinet Médical</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body class="body-afficher-statistiques">
    <?php include 'menu.php'; ?>
    <h1>Statistiques du Cabinet Médical</h1>
    <?php include 'php/statistiques_usagers.php'; ?>
    <?php include 'php/statistiques_medecins.php'; ?>
    </div>
    <?php include 'footer.php'
    ?>

    <script src="js/statistiques.js"></script>
</body>

</html>