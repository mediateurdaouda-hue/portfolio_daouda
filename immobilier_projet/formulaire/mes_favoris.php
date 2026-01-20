<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client') {
    header("Location: auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupérer les favoris du client connecté
$stmt = $pdo->prepare("SELECT f.id AS favori_id, p.*, 
    (SELECT image FROM propriete_images WHERE propriete_id=p.id LIMIT 1) AS image 
    FROM favoris f 
    JOIN proprietes p ON f.propriete_id = p.id 
    WHERE f.client_id = ?");
$stmt->execute([$_SESSION['id']]);
$favoris = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Favoris</title>
  <link rel="stylesheet" href="../proprietes/style_proprietes.css">
  <link rel="stylesheet" href="../rendezvous/style_rdv.css">
</head>
<body>

<div class="header">
  <h1>❤️Mes Favoris</h1>
  <a class="btn" href="tableau_bord_client.php">⬅ Retour</a>
</div>

<div class="grid-container">
<?php 
if (count($favoris) == 0) {
  echo "<p style='text-align:center;'>Aucune propriété ajoutée en favoris pour le moment.</p>";
} else {
  foreach ($favoris as $prop) { ?>
    <div class="propriete-card">
      <img src="../proprietes/uploads/<?php echo $prop['image'] ?? 'default.png'; ?>">
      <h3><?php echo $prop['titre']; ?></h3>
      <p><?php echo $prop['type']; ?> - <?php echo $prop['superficie']; ?> m²</p>
      <p><?php echo number_format($prop['prix']); ?> FCFA</p>
      <a href="../proprietes/details_propriete.php?id=<?php echo $prop['id']; ?>" class="btn">Détails</a>
      <a href="supprimer_favori.php?id_favori=<?php echo $prop['favori_id']; ?>" class="btn favoris-btn" style="background:#c0392b;">Retirer</a>
    </div>
<?php }} ?>
</div>

</body>
</html>
