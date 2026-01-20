<?php
session_start(); // Démarre la session

// Sécurité : seuls les managers peuvent accéder à cette page
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Profil - Manager</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<!-- Bandeau supérieur -->
<div class="header">
  <h1>🌐 Immo+</h1>
  <div class="bouton">
    <a href="../index.php">Accueil</a>
    <a href="deconnexion.php">Déconnexion</a>
  </div>
</div>

<!-- Contenu principal -->
<div class="dashboard-container">

  <!-- Photo de profil manager -->
  <?php 
  $imageProfil = !empty($_SESSION['photo']) ? $_SESSION['photo'] : 'default.png';
  ?>
  <img src="uploads/<?php echo $imageProfil; ?>" alt="Photo de profil">

  <!-- Affiche nom et prénom -->
  <h2><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></h2>

  <!-- Boutons d'action pour manager -->
  <div>
    <a class="btn" href="../proprietes/ajouter_propriete.php">🏠 Ajouter une propriété</a>
    <a class="btn" href="../proprietes/proprietes_en_attente.php">📝 Propriétés à valider</a>
    <a class="btn" href="../proprietes/consulter_proprietes.php">📄 Consulter les propriétés en ligne</a>
    <a class="btn" href="../rendezvous/tableau_rdv_manager.php">📅 Rendez-vous clients</a>
    <a class="btn" href="gestion_utilisateurs.php">👥 Gérer les utilisateurs</a>
    <a class="btn" href="statistiques_manager.php">📊 Statistiques</a>
  </div>

</div>

</body>
</html>
