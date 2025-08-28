<?php
session_start();
require 'config.php'; // connexion à la BDD

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Vérifie que les champs ne sont pas vides
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Si pas d'erreurs, vérifier en base
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            if (($user['role'] ?? '') === 'admin') {
                header('Location: dashboard.php');
            } else {
                header('Location: dashboard_user.php');
            }
            exit;
        } else {
            $errors[] = "Email ou mot de passe incorrect";
        }
    }
}
// Convertit les erreurs PHP en chaîne pour JS
$jsErrors = !empty($errors) ? json_encode(implode("\n", $errors)) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/connexion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    
    
</head>
<body>
<div class="form">
    <h1>Connexion</h1>

   
    <form method="post" action="connexion.php">
    <label for="email">Email:</label>
    <div class="input-container">
        <input type="email" name="email" id="email" required>
        <i class="bi bi-envelope-fill input-icon-right"></i>
    </div>

    <label for="password">Mot de passe:</label>
    <div class="input-container">
        <input type="password" name="password" id="password" required>
        <i class="bi bi-eye input-icon-right" id="toggleIcon" onclick="togglePassword()"></i>
    </div>

    <div class="form-footer">
        <button type="submit">Se connecter</button>
        <p>Pas de compte ? <a href="inscription.php">S'inscrire</a></p>
    </div>
</form>

</div>
<script>
function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}

</script>
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
