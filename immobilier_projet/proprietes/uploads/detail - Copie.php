<?php
session_start();
if (!isset($_GET['id'])) {
    header("Location: consulter_proprietes.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupérer la propriété
$stmt = $pdo->prepare("SELECT * FROM proprietes WHERE id = ?");
$stmt->execute([$_GET['id']]);
$prop = $stmt->fetch();

if (!$prop) {
    echo "Propriété introuvable.";
    exit();
}
// Récupérer les images associées
$stmtImgs = $pdo->prepare("SELECT image FROM propriete_images WHERE propriete_id = ?");
$stmtImgs->execute([$_GET['id']]);
$images = $stmtImgs->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détails de la propriété</title>
  <link rel="stylesheet" href="style_proprietes.css">
  <style>
    .galerie img {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .galerie {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 12px;
    }
  </style>
</head>
<body>

<div class="header">
  <h1><?php echo $prop['titre']; ?></h1>
  <a href="consulter_proprietes.php" class="btn">⬅ Retour</a>
</div>

<div class="galerie">
<?php
if (count($images) > 0) {
    foreach ($images as $img) {
        echo "<img src='uploads/{$img['image']}'>";
    }
} else {
    echo "<p>Aucune image disponible.</p>";
}
?>
</div>

<div class="propriete-card" style="max-width:700px;margin:20px auto;">
  <p><strong>Type :</strong> <?php echo $prop['type']; ?></p>
  <p><strong>Utilisation :</strong> <?php echo $prop['utilisation']; ?></p>
  <p><strong>Option :</strong> <?php echo $prop['option_vente_location']; ?></p>
  <p><strong>Superficie :</strong> <?php echo $prop['superficie']; ?> m²</p>
  <p><strong>Nombre de pièces :</strong> <?php echo $prop['nb_pieces']; ?></p>
  <p><strong>Description :</strong> <?php echo $prop['description']; ?></p>
  <p><strong>Adresse :</strong> <?php echo $prop['adresse']; ?></p>
  <p><strong>Prix :</strong> <?php echo number_format($prop['prix']); ?> FCFA</p>
  <p><strong>Statut :</strong> 
    <?php
      if ($prop['statut'] == 'disponible') {
          echo "<span style='color:green'>Disponible</span>";
      } else {
          echo "<span style='color:red'>Indisponible</span>";
      }
    ?>
  </p>

  <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['manager', 'agent'])) { ?>
    <p><strong>Identifiant du bailleur :</strong> <?php echo $prop['proprietaire_id']; ?></p>
  <?php } ?>

  <div style="margin-top:15px;">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'client' && $prop['statut'] == 'disponible') { ?>
      <a href="../formulaire/prise_rdv.php?id_propriete=<?php echo $prop['id']; ?>" class="btn">📅 Prendre rendez-vous</a>
    <?php } ?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'client') { ?>
      <a href="../formulaire/ajouter_favori.php?id_propriete=<?php echo $prop['id']; ?>" class="btn favoris-btn">❤️ Ajouter aux favoris</a>
    <?php } ?>
  </div>
</div>

</body>
</html>
