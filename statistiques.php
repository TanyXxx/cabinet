<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php include 'PHP/session.php'?>


<!DOCTYPE html>
<html>

<head>
    <title>Statistiques du Cabinet Médical</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body class="body-afficher-statistiques">
    <?php include 'menu.php'; ?>
    <h1>Statistiques du Cabinet Médical</h1>
    <?php include 'PHP/statistiques_usagers.php'; ?>
    <?php include 'PHP/statistiques_medecins.php'; ?>
    </div>
    <?php include 'footer.php'
    ?>

    <script src="js/statistiques.js"></script>
</body>

</html>