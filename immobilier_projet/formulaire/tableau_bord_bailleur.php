<?php
session_start(); // Démarre la session

// Sécurité : seuls les bailleurs peuvent accéder à cette page
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'bailleur') {
    header("Location: auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Profil - Bailleur</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<!-- Bandeau supérieur avec logo et déconnexion -->
<div class="header">
  <h1>🌐 Immo+</h1>
  <div class="bouton">
    <a href="../index.php">Accueil</a>
    <a href="deconnexion.php">Déconnexion</a>
  </div>
</div>

<!-- Contenu principal -->
<div class="dashboard-container">

  <!-- Affiche la photo de profil ou une image par défaut -->
  <?php 
  $imageProfil = !empty($_SESSION['photo']) ? $_SESSION['photo'] : 'default.png';
  ?>
  <img src="uploads/<?php echo $imageProfil; ?>" alt="Photo de profil">

  <!-- Affiche nom et prénom sous la photo -->
  <h2><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></h2>
  <p><?php echo $_SESSION['role']; ?></p>

  <!-- Boutons d'action pour bailleur -->
  <div>
    <a class="btn" href="../proprietes/ajouter_propriete.php">🏠 Ajouter une propriété</a>
    <a class="btn" href="../proprietes/mes_proprietes.php">📄 Mes propriétés</a>
    <a class="btn" href="../proprietes/consulter_proprietes.php">📄 Consulter les propriétés en ligne</a>
  </div>

</div>

</body>
</html>
