<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
	header('Location: connexion.php');
	exit;
}

// Fetch per-user task stats if possible; fallback to zeros on DB errors or missing tables
$taskTotals = [
	'total' => 0,
	'en_cours' => 0,
	'terminees' => 0,
	'a_faire' => 0,
];

try {
	// Aggregate counts by statut for the connected user
	$stmt = $pdo->prepare("SELECT statut, COUNT(*) AS nb FROM taches WHERE utilisateur_id = ? GROUP BY statut");
	$stmt->execute([$_SESSION['user_id']]);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($rows as $row) {
		$statut = strtolower($row['statut'] ?? '');
		$count = (int)($row['nb'] ?? 0);
		if ($statut === 'en_cours' || $statut === 'encours') {
			$taskTotals['en_cours'] += $count;
		} elseif ($statut === 'terminee' || $statut === 'terminée' || $statut === 'terminees' || $statut === 'terminées') {
			$taskTotals['terminees'] += $count;
		} elseif ($statut === 'a_faire' || $statut === 'a faire' || $statut === 'todo' || $statut === 'a_faire') {
			$taskTotals['a_faire'] += $count;
		}
		$taskTotals['total'] += $count;
	}
} catch (Throwable $e) {
	// Silently ignore DB issues; keep zeros
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Utilisateur</title>
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <section class="home-section">
    <div class="text">Tableau de bord - Utilisateur</div>
    <div class="home-content">
      <div class="overview-boxes">
        <div class="box">
          <div class="right-side">
            <div class="box-topic">Mes tâches (total)</div>
            <div class="number"><?php echo (int)$taskTotals['total']; ?></div>
          </div>
          <i class='bx bx-notepad notepad'></i>
        </div>

        <div class="box">
          <div class="right-side">
            <div class="box-topic">Tâches en cours</div>
            <div class="number"><?php echo (int)$taskTotals['en_cours']; ?></div>
          </div>
          <i class='bx bx-notepad notepad two'></i>
        </div>

        <div class="box">
          <div class="right-side">
            <div class="box-topic">Tâches terminées</div>
            <div class="number"><?php echo (int)$taskTotals['terminees']; ?></div>
          </div>
          <i class='bx bx-notepad notepad three'></i>
        </div>

        <div class="box">
          <div class="right-side">
            <div class="box-topic">Tâches à faire</div>
            <div class="number"><?php echo (int)$taskTotals['a_faire']; ?></div>
          </div>
          <i class='bx bx-list-check pin'></i>
        </div>
      </div>
    </div>
  </section>

</body>
</html>

