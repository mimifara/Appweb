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
  <title>Taches</title>
  <link rel="stylesheet" href="css/navbar.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <section class="home-section">
    <div class="text">Bienvenue sur la liste des tâches</div>
  </section>

</body>
</html>
