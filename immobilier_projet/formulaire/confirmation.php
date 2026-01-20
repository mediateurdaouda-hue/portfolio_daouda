<?php
session_start(); // Démarre la session

// Connexion base
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Variable de message
$message = "";

// Traitement du formulaire
if (isset($_POST['identifiant']) && isset($_POST['code'])) {
    $identifiant = $_POST['identifiant'];
    $code = $_POST['code'];

    // Vérifie si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE identifiant = ?");
    $stmt->execute([$identifiant]);
    $user = $stmt->fetch();

    if ($user) {
        // Vérifie si l'activation est déjà faite
        if (empty($user['code_confirmation'])) {
            $message = "✅ Ce compte est déjà activé.";
        }
        // Si le code correspond
        elseif ($user['code_confirmation'] == $code) {
            $stmtUpdate = $pdo->prepare("UPDATE utilisateurs SET code_confirmation = NULL WHERE id = ?");
            $stmtUpdate->execute([$user['id']]);
            $message = "✅ Activation réussie. Vous pouvez maintenant vous connecter.";
        } else {
            $message = "❌ Code d'activation incorrect.";
        }
    } else {
        $message = "❌ Identifiant introuvable.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Activation de compte</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
  <h2>Activation de compte</h2>

  <!-- Affichage du message -->
  <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

  <form action="" method="POST">
    <input type="text" name="identifiant" placeholder="Votre identifiant" required>
    <input type="text" name="code" placeholder="Code d'activation" required>
    <button type="submit">Activer</button>
  </form>

  <p style="text-align:center; margin-top: 15px;">
    <a class="btn" href="auth.php">⬅️ Se connecter</a>
  </p>
</div>

</body>
</html>
