<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: ../formulaire/auth.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../formulaire/gestion_utilisateurs.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Propriétés du bailleur
$stmt = $pdo->prepare("SELECT p.*, 
  (SELECT image FROM propriete_images WHERE propriete_id=p.id LIMIT 1) AS image
  FROM proprietes p WHERE proprietaire_id = ?");
$stmt->execute([$_GET['id']]);
$props = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Propriétés du bailleur</title>
  <link rel="stylesheet" href="style_proprietes.css">
</head>
<body>

<div class="header">
  <h1>📂 Propriétés du bailleur</h1>
  <a class="btn" href="../formulaire/gestion_utilisateurs.php">⬅ Retour</a>
</div>

<div class="grid-container">
  <?php foreach ($props as $p) { ?>
    <div class="propriete-card">
      <img src="uploads/<?php echo $p['image'] ?? 'default.png'; ?>">
      <h3><?php echo $p['titre']; ?></h3>
      <p><?php echo $p['type']; ?> - <?php echo $p['superficie']; ?> m²</p>
      <p><?php echo number_format($p['prix']); ?> FCFA</p>
      <a href="details_propriete.php?id=<?php echo $p['id']; ?>" class="btn">Détails</a>
    </div>
  <?php } ?>
</div>

</body>
</html>
