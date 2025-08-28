<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupérer les statistiques des tâches de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Compter le total des tâches de l'utilisateur
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM taches WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_taches = $stmt->fetch()['total'];

// Compter les tâches en cours
$stmt = $pdo->prepare("SELECT COUNT(*) as en_cours FROM taches WHERE user_id = ? AND statut = 'en_cours'");
$stmt->execute([$user_id]);
$taches_en_cours = $stmt->fetch()['en_cours'];

// Compter les tâches terminées
$stmt = $pdo->prepare("SELECT COUNT(*) as terminees FROM taches WHERE user_id = ? AND statut = 'terminee'");
$stmt->execute([$user_id]);
$taches_terminees = $stmt->fetch()['terminees'];

// Compter les tâches en attente
$stmt = $pdo->prepare("SELECT COUNT(*) as en_attente FROM taches WHERE user_id = ? AND statut = 'en_attente'");
$stmt->execute([$user_id]);
$taches_en_attente = $stmt->fetch()['en_attente'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Tableau de Bord</title>
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <section class="home-section">
    <div class="text">Mon Tableau de Bord</div>
    <div class="home-content">
    <div class="overview-boxes">
      <div class="box">
        <div class="right-side">
          <div class="box-topic">Mes Tâches Total</div>
          <div class="number"><?= $total_taches ?></div>
         </div>
        <i class='bx bx-notepad notepad'></i>  
        </div>
        <div class="box">
        <div class="right-side">
          <div class="box-topic">En Cours</div>
          <div class="number"><?= $taches_en_cours ?></div>
         </div>
        <i class='bx bx-notepad notepad two'></i>  
        </div>
        <div class="box">
        <div class="right-side">
          <div class="box-topic">Terminées</div>
          <div class="number"><?= $taches_terminees ?></div>
         </div>
        <i class='bx bx-notepad notepad three'></i>  
        </div>
        <div class="box">
        <div class="right-side">
          <div class="box-topic">En Attente</div>
          <div class="number"><?= $taches_en_attente ?></div>
         </div>
        <i class='bx bx-plus-circle pin'></i>  
        </div>
      </div>

      <!-- Section d'actions rapides -->
      <div class="quick-actions" style="margin-top: 30px; padding: 0 20px;">
        <h3 style="margin-bottom: 20px; color: #333;">Actions Rapides</h3>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
          <a href="ajouter_tache.php" style="background: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <i class='bx bx-plus-circle'></i>
            Nouvelle Tâche
          </a>
          <a href="taches.php" style="background: #2196F3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <i class='bx bx-notepad'></i>
            Voir Mes Tâches
          </a>
          <a href="parametres.php" style="background: #FF9800; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <i class='bx bx-cog'></i>
            Paramètres
          </a>
        </div>
      </div>
    </div>
  </section>

</body>
</html>