<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}
// Récupération des utilisateurs
try {
  /*$stmt = $pdo->query("SELECT id, nom, prenoms, email FROM utilisateurs");
  $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);*/
  $stmt = $pdo->prepare("SELECT id, nom, prenoms, email FROM utilisateurs WHERE role = :role");
  $stmt->execute(['role' => 'user']);
  $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Voir les utilisateurs</title>
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/admin_voir_utilisateurs.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <section class="home-section">
  <div class="text">Liste des utilisateurs</div>
  <div class="home-content">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Prénoms</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($utilisateurs)) : ?>
          <?php foreach ($utilisateurs as $user) : ?>
            <tr>
              <td><?= htmlspecialchars($user['id']) ?></td>
              <td><?= htmlspecialchars($user['nom']) ?></td>
              <td><?= htmlspecialchars($user['prenoms']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td>
                 <form action="supprimer_utilisateur.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                  <button type="submit" class="btn-supprimer">Supprimer</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr><td colspan="4" style="text-align:center;">Aucun utilisateur trouvé.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

</body>
</html>
