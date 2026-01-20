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
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .gallery {
      width: 85%;
      margin: 0 auto;
      text-align: center;
    }
    .main-image-container {
      position: relative;
    }
    .main-image {
      width: 100%;
      height: 85vh;
      object-fit: cover;
      border-radius: 10px;
    }


    /*.main-image {
      width: 100%;
      height: 85vh;
      border-radius: 10px;
    }*/
    .thumbnails {
      display: flex;
      overflow-x: auto;
      margin-top: 10px;
      justify-content: center;
    }
    .thumbnail {
     flex: 0 0 auto;
     width: 100px;
     height: 60px;
     margin: 5px;
     opacity: 0.7;
     cursor: pointer;
     border-radius: 5px;
     object-fit: cover;
    }

    /*.thumbnail {
      flex: 0 0 auto;
      width: 100px;
      height: 60px;
      margin: 5px;
      opacity: 0.6;
      cursor: pointer;
      border-radius: 5px;
    }*/
    .thumbnail:hover {
      opacity: 1;
    }
    .arrow {
      cursor: pointer;
      font-size: 2em;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      color: white;
      background-color: rgba(0, 0, 0, 0.5);
      padding: 0 12px;
      z-index: 1000;
    }
    .arrow-left {
      left: 0;
    }
    .arrow-right {
      right: 0;
    }
    .propriete-details {
      width: 85%;
      margin: 30px auto;
      background: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
    }
    .propriete-details h2 {
      margin-top: 0;
    }
    .btn {
      display: inline-block;
      background: #ff6600;
      color: white;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 8px;
      margin: 5px 5px 0;
    }
    .btn:hover {
      background: #e65c00;
    }
    .favoris-btn {
      background: #e74c3c;
    }
    .favoris-btn:hover {
      background: #c0392b;
    }
  </style>
</head>
<body>

<div class="gallery">
  <div class="main-image-container">
    <span class="arrow arrow-left" onclick="prevImage()">&#10094;</span>
    <img id="main-image" class="main-image" src="" alt="Image principale">
    <span class="arrow arrow-right" onclick="nextImage()">&#10095;</span>
  </div>

  <div class="thumbnails" id="thumbnails">
    <?php
    $i = 0;
    foreach ($images as $img) {
        echo "<img class='thumbnail' src='uploads/{$img['image']}' onclick='showImage($i)'>";
        $i++;
    }
    ?>
  </div>
</div>

<div class="propriete-details">
  <h2><?php echo $prop['titre']; ?></h2>
  <p><strong>Type :</strong> <?php echo $prop['type']; ?></p>
  <p><strong>Utilisation :</strong> <?php echo $prop['utilisation']; ?></p>
  <p><strong>Option :</strong> <?php echo $prop['option_vente_location']; ?></p>
  <p><strong>Superficie :</strong> <?php echo $prop['superficie']; ?> m²</p>
  <p><strong>Pièces :</strong> <?php echo $prop['nb_pieces']; ?></p>
  <p><strong>Description :</strong> <?php echo $prop['description']; ?></p>
  <p><strong>Adresse :</strong> <?php echo $prop['adresse']; ?></p>
  <p><strong>Prix :</strong> <?php echo number_format($prop['prix']); ?> FCFA</p>

  <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['manager', 'agent'])) { ?>
    <p><strong>Identifiant du bailleur :</strong> <?php echo $prop['proprietaire_id']; ?></p>
  <?php } ?>

  <div>
    <a href="consulter_proprietes.php" class="btn">⬅ Retour</a>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'client' && $prop['statut'] == 'disponible') { ?>
      <a href="../formulaire/prise_rdv.php?id_propriete=<?php echo $prop['id']; ?>" class="btn">📅 Prendre rendez-vous</a>
      <a href="../formulaire/ajouter_favori.php?id_propriete=<?php echo $prop['id']; ?>" class="btn favoris-btn">❤️ Favoris</a>
    <?php } ?>
  </div>
</div>

<script>
const images = [
<?php
foreach ($images as $img) {
    echo "'uploads/{$img['image']}',";
}
?>
];
let currentIndex = 0;

function showImage(index) {
  currentIndex = index;
  document.getElementById('main-image').src = images[index];
}

function prevImage() {
  currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
  showImage(currentIndex);
}

function nextImage() {
  currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
  showImage(currentIndex);
}

showImage(currentIndex);
</script>

</body>
</html>
