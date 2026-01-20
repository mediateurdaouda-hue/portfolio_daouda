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

// Rdv du client ou agent
$stmt = $pdo->prepare("SELECT r.*, p.titre, p.type, p.superficie, p.prix,
  (SELECT image FROM propriete_images WHERE propriete_id=p.id LIMIT 1) AS image
  FROM rendezvous r
  JOIN proprietes p ON r.propriete_id = p.id
  WHERE r.client_id = ? OR r.agent_id = ?
  ORDER BY r.date_demande DESC");
$stmt->execute([$_GET['id'], $_GET['id']]);
$rdvs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Rendez-vous utilisateur</title>
  <link rel="stylesheet" href="style_rdv.css">
</head>
<body>

<div class="header">
  <h1>📅 Rendez-vous de l'utilisateur</h1>
  <a class="btn" href="../formulaire/gestion_utilisateurs.php">⬅ Retour</a>
</div>

<div class="grid-container">
  <?php foreach ($rdvs as $r) { ?>
    <div class="rdv-card">
      <img src="../proprietes/uploads/<?php echo $r['image'] ?? 'default.png'; ?>" alt="photo">
      <h3><?php echo $r['titre']; ?></h3>
      <p><?php echo $r['type']; ?> - <?php echo $r['superficie']; ?> m²</p>
      <p><?php echo number_format($r['prix']); ?> FCFA</p>
      <p>Statut : <?php echo ucfirst($r['statut']); ?></p>
    </div>
  <?php } ?>
</div>

</body>
</html>
