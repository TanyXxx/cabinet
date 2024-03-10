<?php
require_once 'connexionDB.php'; // Assurez-vous que cela pointe vers votre fichier de connexion

$stmt = $conn->query("SELECT ID_Utilisateur, MotDePasse FROM utilisateurs");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $hashedPassword = password_hash($user['MotDePasse'], PASSWORD_DEFAULT);
    $updateStmt = $conn->prepare("UPDATE utilisateurs SET MotDePasse = :hashedPassword WHERE ID_Utilisateur = :id");
    $updateStmt->execute([
        ':hashedPassword' => $hashedPassword,
        ':id' => $user['ID_Utilisateur']
    ]);
}

echo "Mots de passe mis à jour avec succès.";
