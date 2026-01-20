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

/*SELECTIONER UNE SEULE IMAGE */
$Imgs = $pdo->prepare("SELECT image FROM propriete_images WHERE propriete_id = ? LIMIT 1");
$Imgs->execute([$_GET['id']]);
$img = $Imgs->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="hotel.css">
    <title>🏡 details de la propriete</title>
</head>
<body>
    <div class="haut">
        <div class="bloquer">
            <div class="menu">
                <h4>paradis</h4>
                <ul>
                    <li>
                        <a href="../index.php">ACCUEIL</a>
                    </li>
                    <li>
                        <a href="../chambre/chambre.php">LES CHAMBRES</a>
                    </li>
                    <li>
                        <a href="../apropo.html">A PROPOS</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="haut-bas">
            <h1>hotel Famasso de Banfora</h1>
            <div class="flex">
                <div class="position">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                        <path d="M12.166 8.94c-.309 1.093-.91 2.256-1.662 3.406-.746 1.144-1.59 2.282-2.448 3.316-.38.46-.745.875-1.076 1.208-.33-.333-.696-.748-1.076-1.208-.857-1.034-1.702-2.172-2.448-3.316-.752-1.15-1.353-2.313-1.662-3.406C1.605 7.787 1.5 6.868 1.5 6A6.5 6.5 0 0 1 8 0a6.5 6.5 0 0 1 6.5 6c0 .868-.105 1.787-.334 2.94zm-1.797-.485c.224-.793.298-1.513.298-2.455A5.5 5.5 0 0 0 8 1a5.5 5.5 0 0 0-5.5 5c0 .942.074 1.662.298 2.455.227.807.63 1.723 1.233 2.686.745 1.14 1.59 2.278 2.447 3.311.335.404.672.77 1.022 1.094.35-.324.687-.69 1.022-1.094.857-1.033 1.702-2.171 2.447-3.311.603-.963 1.006-1.879 1.233-2.686zM8 8.5a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
                    </svg>
                        <p>Tatana, secteur 9, BANFORA BURKINA FASO</p>
                </div>
            
                <div class="bouton">
                    <button>Voir les disponibilité</button>
                </div>
            </div>
        </div>
    </div>

    <div class="gallery">
        <div class="main-image-container">
            <span class="arrow arrow-left" onclick="prevImage()">&#10094;</span>
            <img id="main-image" class="main-image" src="../uploads/<?php echo $img['image'] ?? 'default.png'; ?>" alt="Image principale">
            <span class="arrow arrow-right" onclick="nextImage()">&#10095;</span>
        </div>
        <div class="thumbnails" id="thumbnails">
            <?php
                $i=0;
                foreach ($images as $img){?>
                    <img class="thumbnail" <?php echo "<img src='../uploads/{$img['image']}'>";?> alt="Image <?php $i; ?>" onclick="showImage($i)">
                    <?php $i++; ?>
                <?php } ?>
<!--
            
            <img class="thumbnail" src="../image/image1.jpg" alt="Image 1" onclick="showImage(0)">
            <img class="thumbnail" src="../image/image2.jpg" alt="Image 2" onclick="showImage(1)">
            <img class="thumbnail" src="../image/image3.jpg" alt="Image 3" onclick="showImage(2)">
            <img class="thumbnail" src="../image/image4.jpg" alt="Image 4" onclick="showImage(3)">
            <img class="thumbnail" src="../image/image5.jpg" alt="Image 5" onclick="showImage(4)">
            <img class="thumbnail" src="../image/image6.jpg" alt="Image 6" onclick="showImage(5)">
            <img class="thumbnail" src="../image/image7.jpg" alt="Image 7" onclick="showImage(6)">
            <img class="thumbnail" src="../image/image8.jpg" alt="Image 8" onclick="showImage(7)">
            <img class="thumbnail" src="../image/image9.jpg" alt="Image 9" onclick="showImage(8)">
            <img class="thumbnail" src="../image/img1.jpg" alt="Image 10" onclick="showImage(9)">
            <img class="thumbnail" src="../image/img2.jpg" alt="Image 11" onclick="showImage(10)">
            <img class="thumbnail" src="../image/img3.jpg" alt="Image 12" onclick="showImage(11)">
            <img class="thumbnail" src="../image/img10.jpg" alt="Image 13" onclick="showImage(12)">
            <img class="thumbnail" src="../image/image13.jpeg" alt="Image 13" onclick="showImage(13)">

            <!-- Ajoutez plus d'images selon vos besoins -->
        </div>
    </div>

    <div class="reservation">
        <p class="gras">Contacter l'hébergement pour connaître les disponibilités.</p>
        <p>Il existe des hôtels similaires disponibles. <a href="###">Voir tout</a></p>
    </div>
    
    <footer id="footer" class="section footer" style="margin-top: 50px;">
        <div class="copyright">
            copyright2023 tous droit reservés
            <a href="#">ClementGnoumou</a> 
        </div>
    </footer>

    <div class="galerie">
        <?php
        if (count($images) > 0) {
            foreach ($images as $img) {
                echo "<img src='../uploads/{$img['image']}'>";
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

    <script src="hotel.js"></script>
</body>
</html>