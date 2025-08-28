<?php
if (!isset($_SESSION)) session_start();
require_once 'config.php';

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$isAdmin = ($user['role'] === 'admin');
?>
<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/navbar.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
<!-- SIDEBAR HTML -->
<div class="sidebar">
  <div class="logo-details">
    <i class='bx bx-package icon'></i>
    <div class="logo_name">G-Tâches</div>
    <i class='bx bx-menu' id="btn"></i>
  </div>
  <ul class="nav-list">
    <li>
      <i class='bx bx-search'></i>
      <input type="text" placeholder="Search...">
      <span class="tooltip">Search</span>
    </li>

    <li>
      <a href="<?= $isAdmin ? 'dashboard.php' : 'dashboard_user.php' ?>">
        <i class='bx bx-grid-alt'></i>
        <span class="links_name">Tableau de bord</span>
      </a>
      <span class="tooltip">Tableau de bord</span>
    </li>

    <?php if ($isAdmin): ?>
      <li>
        <a href="admin_voir_utilisateurs.php">
          <i class='bx bx-user-pin'></i>
          <span class="links_name">Utilisateurs</span>
        </a>
        <span class="tooltip">Utilisateurs</span>
      </li>

      <li>
        <a href="admin_voir_taches.php">
          <i class='bx bx-notepad'></i>
          <span class="links_name">Tâches</span>
        </a>
        <span class="tooltip">Tâches</span>
      </li>

      <li>
        <a href="admin_voir_statistiques.php">
          <i class='bx bx-pie-chart-alt'></i>
          <span class="links_name">Statistiques</span>
        </a>
        <span class="tooltip">Statistiques</span>
      </li>

    <?php else: ?>
      <li>
        <a href="taches.php">
          <i class='bx bx-notepad'></i>
          <span class="links_name">Mes Tâches</span>
        </a>
        <span class="tooltip">Mes Tâches</span>
      </li>

      <li>
        <a href="ajouter_tache.php">
          <i class='bx bx-plus-circle'></i>
          <span class="links_name">Ajouter Tâche</span>
        </a>
        <span class="tooltip">Ajouter Tâche</span>
      </li>
    <?php endif; ?>

    <li>
      <a href="parametres.php">
        <i class='bx bx-cog'></i>
        <span class="links_name">Paramètres</span>
      </a>
      <span class="tooltip">Paramètres</span>
    </li>

    <li class="profile">
      <div class="profile-details">
        <div class="name_job">
          <div class="name"><?= htmlspecialchars($user['Prenoms']) ?></div>
          <div class="job"><?= htmlspecialchars($user['role']) ?></div>
        </div>
      </div>
      <a href="deconnexion.php"><i class='bx bx-log-out' id="log_out"></i></a>
    </li>
  </ul>
</div>

<!-- SIDEBAR SCRIPT -->
<script>
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");
  let searchBtn = document.querySelector(".bx-search");

  closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
  });

  searchBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
  });

  function menuBtnChange() {
    if (sidebar.classList.contains("open")) {
      closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
    } else {
      closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
    }
  }
</script>
</body>
</html>