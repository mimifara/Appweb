<?php
session_start();
require 'config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupère les infos de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Si l'utilisateur n'existe pas (cas rare)
if (!$user) {
    session_destroy();
    header('Location: connexion.php');
    exit;
}

// Séparation admin / user
$isAdmin = ($user['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="profil">
        <h1>Bienvenue, <?= htmlspecialchars($user['Prenoms']) ?> !</h1>
        <p>Rôle : <?= htmlspecialchars($user['role']) ?></p>
        </div>
        <?php if ($isAdmin): ?>
            <div class="admin-dashboard">
            <h2>Tableau de bord Administrateur</h2>
            <ul>
                <li><a href="admin_voir_utilisateurs.php">Gérer les utilisateurs</a></li>
                <li><a href="admin_voir_taches.php">Voir toutes les tâches</a></li>
                <li><a href="admin_statistiques.php">Statistiques</a></li>
            </ul>
            </div>
        <?php else: ?>
            <div class="user-dashboard">
            <h2>Tableau de bord Utilisateur</h2>
            <ul>
                <li><a href="taches.php">Mes tâches</a></li>
                <li><a href="ajouter_tache.php">Ajouter une tâche</a></li>
            </ul>
            </div>
        <?php endif; ?>

        <p><a href="deconnexion.php">Se déconnecter</a></p>
    </div>
</body>
</html>
