<?php
session_start(); // Démarre la session

// Sécurité : seuls agents et managers peuvent accéder
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], ['agent', 'manager'])) {
    header("Location: ../formulaire/auth.php");
    exit();
}

// Connexion base
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupère les propriétés en attente
$stmt = $pdo->prepare("SELECT p.*, (SELECT image FROM propriete_images WHERE propriete_id = p.id LIMIT 1) AS image 
                       FROM proprietes p
                       WHERE p.validation = 'en_attente'
                       ORDER BY p.date_ajout DESC");
$stmt->execute();
$proprietes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Propriétés à valider</title>
  <link rel="stylesheet" href="style_proprietes.css">
</head>
<body>

<div class="header">
  <h1>📝 Propriétés en attente</h1>
  <div class="bouton">
    <a class="btn" href="../index.php">Accueil</a>
    <a class="btn" href="../formulaire/tableau_bord_<?php echo $_SESSION['role']; ?>.php">Mon tableau de bord</a>
  </div>
  
</div>

<div class="grid-container">

  <?php foreach ($proprietes as $prop) { ?>
    <div class="propriete-card">
      <img src="uploads/<?php echo $prop['image'] ?? 'default.png'; ?>" alt="Image propriété">

      <h3><?php echo htmlspecialchars($prop['titre']); ?></h3>
      <p><?php echo htmlspecialchars($prop['type']); ?> - <?php echo $prop['superficie']; ?> m²</p>
      <p><strong><?php echo number_format($prop['prix']); ?> FCFA</strong></p>

      <!-- Actions -->
      <a href="details_propriete.php?id=<?php echo $prop['id']; ?>" class="btn">Voir détails</a>
      <a href="valider_propriete.php?id=<?php echo $prop['id']; ?>&action=valider" class="btn">✅ Valider</a>
      <a href="valider_propriete.php?id=<?php echo $prop['id']; ?>&action=refuser" class="btn">❌ Refuser</a>
    </div>
  <?php } ?>

</div>

</body>
</html>
