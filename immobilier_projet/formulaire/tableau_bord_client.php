<?php
session_start(); // Démarre la session

// Sécurité : seuls les clients peuvent accéder à cette page
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client') {
    header("Location: auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Profil - Client</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<!-- Bandeau supérieur avec titre et bouton déconnexion -->
<div class="header">
  <h1>🌐 Immo+</h1>
  <a href="mes_favoris.php" class="btn favoris-link">
  ❤️ <span>Mes Favoris</span>
  </a>
  <div class="bouton">
    <a href="../index.php">Accueil</a>
    <a href="deconnexion.php">Déconnexion</a>
  </div>
</div>

<!-- Contenu principal du tableau de bord -->
<div class="dashboard-container">

  <!-- Photo de profil (image par défaut si aucune photo envoyée) -->
  <?php 
  $imageProfil = !empty($_SESSION['photo']) ? $_SESSION['photo'] : 'default.png';
  ?>
  <img src="uploads/<?php echo $imageProfil; ?>" alt="Photo de profil">

  <!-- Affiche nom et prénom sous la photo -->
  <h2><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></h2>

  <!-- Boutons d'action -->
  <div>
    <a class="btn" href="../proprietes/consulter_proprietes.php">📄 Consulter les propriétés</a>
    <a class="btn" href="../rendezvous/tableau_rdv_client.php">📅 Mes rendez-vous</a>
    <a class="btn" href="modifier_profil.php">⚙️ Modifier mon profil</a>
    <a href="mes_favoris.php" class="btn">❤️ Mes Favoris</a>

  </div>

</div>

</body>
</html>
