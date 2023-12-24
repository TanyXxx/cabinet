<?php
include 'BD.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $sql = "SELECT * FROM usagers";
    $stmt = $pdo->query($sql);

    $usagers = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            array_push($usagers, $row);
        }
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
