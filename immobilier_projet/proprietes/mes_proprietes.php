<?php
session_start(); // Démarre la session

// Sécurité : uniquement bailleur
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'bailleur') {
    header("Location: ../formulaire/auth.php");
    exit();
}

// Connexion base
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupère les propriétés du bailleur connecté
$stmt = $pdo->prepare("SELECT p.*, (SELECT image FROM propriete_images WHERE propriete_id = p.id LIMIT 1) AS image 
                       FROM proprietes p
                       WHERE p.proprietaire_id = ?
                       ORDER BY p.date_ajout DESC");
$stmt->execute([$_SESSION['id']]);
$proprietes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Propriétés</title>
  <link rel="stylesheet" href="style_proprietes.css">
</head>
<body>

<div class="header">
  <h1>🏡 Mes Propriétés</h1>
  <a class="btn" href="../formulaire/tableau_bord_bailleur.php">⬅ Retour</a>
</div>

<div class="grid-container">

  <?php foreach ($proprietes as $prop) { ?>
    <div class="propriete-card">
      <img src="uploads/<?php echo $prop['image'] ?? 'default.png'; ?>" alt="Image propriété">

      <h3><?php echo htmlspecialchars($prop['titre']); ?></h3>
      <p><?php echo $prop['type']; ?> - <?php echo $prop['superficie']; ?> m²</p>
      <p><strong><?php echo number_format($prop['prix']); ?> FCFA</strong></p>
      <p><strong>Validation :</strong> <?php echo ucfirst($prop['validation']); ?></p>
      <p><strong>Statut :</strong> <?php echo ucfirst($prop['statut']); ?></p>

      <a href="details_propriete.php?id=<?php echo $prop['id']; ?>" class="btn">Voir détails</a>
    </div>
  <?php } ?>

</div>

</body>
</html>
