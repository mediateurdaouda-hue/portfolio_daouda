<?php
session_start(); // Démarre la session

// Sécurité : seuls les agents peuvent accéder à cette page
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'agent') {
    header("Location: auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Profil - Agent</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<!-- Bandeau supérieur avec titre et déconnexion -->
<div class="header">
  <h1>🌐Immo+</h1>
  <div class="bouton">
    <a href="../index.php">Accueil</a>
    <a href="deconnexion.php">Déconnexion</a>
  </div>
</div>

<!-- Contenu principal -->
<div class="dashboard-container">

  <!-- Photo de profil de l'agent -->
  <?php 
  $imageProfil = !empty($_SESSION['photo']) ? $_SESSION['photo'] : 'default.png';
  ?>
  <img src="uploads/<?php echo $imageProfil; ?>" alt="Photo de profil">

  <!-- Affiche nom et prénom -->
  <h2><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></h2>

  <!-- Boutons d'action pour agent -->
  <div>
    <a class="btn" href="../proprietes/ajouter_propriete.php">🏠 Ajouter une propriété</a>
    <a class="btn" href="../proprietes/proprietes_en_attente.php">📝 Propriétés à valider</a>
    <a class="btn" href="../proprietes/consulter_proprietes.php">📄 Consulter les propriétés en ligne</a>
    <a class="btn" href="../rendezvous/tableau_rdv_agent.php">📅 Rendez-vous</a>
  </div>

</div>

</body>
</html>
