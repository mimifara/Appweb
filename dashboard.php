<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <section class="home-section">
    <div class="text">Tableau de bord</div>
    <div class="home-content">
    <div class="overview-boxes">
      <div class="box">
        <div class="right-side">
          <div class="box-topic">Tâches total</div>
          <div class="number">11</div>
         </div>
        <i class='bx bx-notepad notepad'></i>  
        </div>
        <div class="box">
        <div class="right-side">
          <div class="box-topic">Tâches en cours</div>
          <div class="number">11</div>
         </div>
        <i class='bx bx-notepad notepad two'></i>  
        </div>
        <div class="box">
        <div class="right-side">
          <div class="box-topic">Tâches Terminé</div>
          <div class="number">11</div>
         </div>
        <i class='bx bx-notepad notepad three'></i>  
        </div>
        <div class="box">
        <div class="right-side">
          <div class="box-topic">Utilisateurs</div>
          <div class="number">11</div>
         </div>
        <i class='bx bx-user pin'></i>  
        </div>
      </div>
  </section>

</body>
</html>
