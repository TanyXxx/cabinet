<?php
session_start();
include 'BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT ID_Utilisateur, NomUtilisateur, MotDePasse FROM utilisateurs WHERE NomUtilisateur = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        if (password_verify($password, $user['MotDePasse'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['ID_Utilisateur'];
            $_SESSION['username'] = $user['NomUtilisateur'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Identifiants incorrects";
        }
    } else {
        $error = "Identifiants incorrects";
    }
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit;
}
?>
