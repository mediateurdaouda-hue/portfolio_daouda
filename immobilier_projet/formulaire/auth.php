<?php
session_start(); // Démarre la session

// Si un utilisateur est déjà connecté, on le redirige directement vers son tableau de bord
if (isset($_SESSION['id'])) {
    header("Location: tableau_bord_" . $_SESSION['role'] . ".php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Authentification</title>

  <!-- CSS pour cette page -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="proprietes/details_proprietes.css">

  <!-- JS pour gérer l'affichage des formulaires -->
  <script src="script.js" defer></script>
</head>
<body>

<style>
  .bloquer{
    margin-bottom: 100px;
  }
  
.bloquer{
    background-color: #e65c00;
    color: #fff;
    height: 50px;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 10;

}

.bloquer .menu{
    display: flex;
    justify-content: space-between;
    margin-left: 150px;
    margin-right: 30px;
}
.bloquer .menu ul{
    width: 300px;
    display: flex;
    justify-content: space-between;
    list-style: none;
}
.bloquer .menu ul a:hover{
    color: orange;
}
.bloquer .menu ul a{
    color: #fff;
    text-decoration: none;
}

</style>
    <div class="bloquer">
            <div class="menu">
                <h4>Immo+</h4>
                <ul>
                    <li>
                        <a href="../index.php">ACCUEIL</a>
                    </li>
                    <li>
                        <a href="../proprietes/consulter_proprietes.php">PROPRIETES</a>
                    </li>
                    <li>
                        <a href="../a propo.php">A PROPOS</a>
                    </li>
                </ul>
            </div>
    </div>

<div class="form-container">
  <h2>Connexion ou Inscription</h2>

  <!-- Boutons pour basculer entre les formulaires -->
  <div class="button-group">
    <button onclick="afficherConnexion()">Se connecter</button>
    <button onclick="afficherInscription()">S'inscrire</button>
  </div>

  <!-- Formulaire de connexion (masqué par défaut) -->
  <div id="connexion-form" class="form-block">
    <form action="connexion.php" method="POST">
      <input type="text" name="identifiant" placeholder="Identifiant" required>
      <input type="password" name="motdepasse" placeholder="Mot de passe" required>
      <button type="submit">Connexion</button>
    </form>
  </div>

  <!-- Formulaire d'inscription (masqué par défaut) -->
  <div id="inscription-form" class="form-block" style="display:none;">
    <form action="inscription.php" method="POST" enctype="multipart/form-data">
      
      <!-- Nom -->
      <input type="text" name="nom" placeholder="Nom" required>

      <!-- Prénom -->
      <input type="text" name="prenom" placeholder="Prénom" required>

      <!-- Identifiant -->
      <input type="text" name="identifiant" placeholder="Identifiant" required>

      <!-- Mot de passe -->
      <input type="password" name="motdepasse" placeholder="Mot de passe" required>

      <!-- Téléphone -->
      <input type="text" name="telephone" placeholder="Téléphone" required>

      <!-- Email -->
      <input type="email" name="email" placeholder="Adresse email" required>

      <!-- Sélection du rôle -->
      <select name="role" required>
        <option value="">-- Rôle --</option>
        <option value="client">Client</option>
        <option value="bailleur">Bailleur</option>
        <option value="agent">Agent</option>
        <option value="manager">Manager</option>
      </select>

      <!-- Upload photo optionnel -->
      <label>Photo de profil (optionnel)</label>
      <input type="file" name="photo" accept="image/*">

      <button type="submit">Inscription</button>
    </form>
  </div>

  <!-- Message éventuel en GET -->
  <?php
  if (isset($_GET['msg'])) {
      echo "<div class='message'>".urldecode($_GET['msg'])."</div>";
  }
  ?>
 <p style="text-align:center; margin-top: 15px;">
    <a class="btn" href="confirmation.php">Activer un compte</a>
  </p>

</div>

</body>
</html>
