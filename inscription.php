<?php
session_start(); // Démarre la session pour pouvoir enregistrer l'utilisateur connecté
require 'config.php'; // Fichier de connexion à la base de données (contient l’objet $pdo)

$errors = []; // Tableau pour stocker les erreurs

// Si le formulaire a été soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère et nettoie les champs
    $nom = trim($_POST['nom'] ?? '');
    $prenoms = trim($_POST['Prenoms'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation des champs
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }

    if (empty($prenoms)) {
        $errors[] = "Le prénom est requis.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    if ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifie si l'email est déjà utilisé
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        }
    }

    // Si aucune erreur, on insère l'utilisateur
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT); // Hash du mot de passe
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, Prenoms, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prenoms, $email, $hash]);
        
        // On connecte automatiquement l'utilisateur
        $_SESSION['user_id'] = $pdo->lastInsertId(); // Récupère l'ID du nouvel utilisateur
        header('Location: dashboard.php'); // Redirection vers la page principale
        exit;
    }
}
// Convertit les erreurs PHP en chaîne pour JS
$jsErrors = !empty($errors) ? json_encode(implode("\n", $errors)) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
    <div class="form">
    <h1>Créer un compte</h1>

    <!-- Affichage des erreurs s’il y en a -->
   

    <!-- Formulaire d'inscription -->
    <form method="post" action="inscription.php">
        <label>Nom : <input type="text" name="nom" required></label><br><br>
        <label>Prénoms : <input type="text" name="Prenoms" required></label><br><br>
        <label>Email : <input type="email" name="email" required></label><br><br>
        <label>Mot de passe : <input type="password" name="password" required></label><br><br>
        <label>Confirmer mot de passe : <input type="password" name="password_confirm" required></label><br><br>
        <div class="form-footer">
            <button type="submit">S’inscrire</button>
             <p>Déjà inscrit ? <a href="connexion.php">Connecte-toi ici</a></p>
        </div>
    </form>

    
            </div>    
            <?php if ($jsErrors): ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Crée l'overlay
    const overlay = document.createElement("div");
    overlay.className = "error-overlay";

    // Crée la boîte de message
    const box = document.createElement("div");
    box.className = "error-popup";

    const message = document.createElement("p");
    message.textContent = <?= $jsErrors ?>;
    box.appendChild(message);

    // Crée le bouton OK
    const okBtn = document.createElement("button");
    okBtn.textContent = "OK";
    okBtn.className = "ok-button";
    okBtn.onclick = () => {
        document.body.removeChild(overlay);
        document.querySelector(".form").classList.remove("blurred");
    };
    box.appendChild(okBtn);

    overlay.appendChild(box);
    document.body.appendChild(overlay);

    // Ajoute le flou au formulaire
    document.querySelector(".form").classList.add("blurred");
});
</script>
<?php endif; ?>
</body>
</html>
