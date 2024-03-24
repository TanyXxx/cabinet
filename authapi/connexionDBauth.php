<?php
$servername = "mysql-noahbeugnet.alwaysdata.net";
$dbname = "noahbeugnet_cabinetauth";
$username = "352917_etu";
$password = "iutinfo";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Retiré l'écho de "Connexion réussie" pour éviter des outputs inutiles.
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>