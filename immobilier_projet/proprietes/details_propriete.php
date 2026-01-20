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
  <link rel="stylesheet" href="details_propriete.css">
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

<div class="haut">
        <div class="bloquer">
            <div class="menu">
                <h4>Immo+</h4>
                <ul>
                    <li>
                        <a href="../index.php">ACCUEIL</a>
                    </li>
                    <li>
                        <a href="consulter_proprietes.php">PROPRIETES</a>
                    </li>
                    <li>
                        <a href="../apropo.html">A PROPOS</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="haut-bas">
            <h1><strong><?php echo $prop['titre']; ?></strong></h1>
            <div class="flex">
                <div class="position">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                        <path d="M12.166 8.94c-.309 1.093-.91 2.256-1.662 3.406-.746 1.144-1.59 2.282-2.448 3.316-.38.46-.745.875-1.076 1.208-.33-.333-.696-.748-1.076-1.208-.857-1.034-1.702-2.172-2.448-3.316-.752-1.15-1.353-2.313-1.662-3.406C1.605 7.787 1.5 6.868 1.5 6A6.5 6.5 0 0 1 8 0a6.5 6.5 0 0 1 6.5 6c0 .868-.105 1.787-.334 2.94zm-1.797-.485c.224-.793.298-1.513.298-2.455A5.5 5.5 0 0 0 8 1a5.5 5.5 0 0 0-5.5 5c0 .942.074 1.662.298 2.455.227.807.63 1.723 1.233 2.686.745 1.14 1.59 2.278 2.447 3.311.335.404.672.77 1.022 1.094.35-.324.687-.69 1.022-1.094.857-1.033 1.702-2.171 2.447-3.311.603-.963 1.006-1.879 1.233-2.686zM8 8.5a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
                    </svg>
                        <p> <?php echo $prop['adresse']; ?> BURKINA FASO</p>
                </div>
            
                <div class="bouton">
                    <button onclick="window.location.href = 'consulter_proprietes.php'">Voir les disponibilité</button>
                </div>
            </div>
        </div>
    </div>

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
      <a href="../rendezvous/prise_rdv.php?id=<?php echo $prop['id']; ?>" class="btn">📅 Prendre rendez-vous</a>
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
