
<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

$conditions = ["p.validation = 'validee'"];
$params = [];

if (!empty($_GET['type'])) {
    $conditions[] = "p.type = ?";
    $params[] = $_GET['type'];
}

if (!empty($_GET['option'])) {
    $conditions[] = "p.option_vente_location = ?";
    $params[] = $_GET['option'];
}

if (!empty($_GET['utilisation'])) {
    $conditions[] = "p.utilisation = ?";
    $params[] = $_GET['utilisation'];
}

if (!empty($_GET['recherche'])) {
    $conditions[] = "(p.titre LIKE ? OR p.adresse LIKE ?)";
    $params[] = "%".$_GET['recherche']."%";
    $params[] = "%".$_GET['recherche']."%";
}

$sql = "SELECT p.*, (SELECT image FROM propriete_images WHERE propriete_id = p.id LIMIT 1) AS image 
        FROM proprietes p
        WHERE " . implode(" AND ", $conditions) . "
        ORDER BY p.date_ajout DESC LIMIT 4";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$proprietes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>accueil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="proprietes/style_proprietes.css">
    
</head>
<body>
    <!-- section acceuil -->
     <section id="container">
      <header>
        <div class="logo">
            <p>Immo+</p>
        </div>
        <!-- menu responsive -->
        <div class="menu">

                <ul class="menu">
            <li><a href="#container">ACCUEIL</a></li>
            
            <li class="dropdown">
                <a href="#">TYPES</a>
                <div class="dropdown-content">
                    <a href="proprietes/appartements.php">APPARTEMENTS</a>
                    <a href="proprietes/villas.php">VILLAS</a>
                    <a href="proprietes/commerces.php">COMMERCES</a>
                    <a href="proprietes/terrains.php">TERRAINS</a>
                </div>
            </li>
            <li><a href="a propo.php">A PROPOS</a></li>
            
        </ul>
      </header>
      <div class="container-text">
          <h1>Sécurisée et Agreable pour tous</h1>
          <p>Location, vente ou gestion : nous vous accompagnons à chaque étape.</p>
          <a href="formulaire/auth.php">Se connecter</a>
      </div>

    </div>

        
    </section> 
    

     
    <section id="gallerie">
            <p class="tittle-section">TYPE DE PROPRIETE</p>
            <h1 class="sub-tittle-section"> </h1>
            <div class="liste-photos">
                <div class="gallerie-img">
                    <a href="proprietes/appartements.php">
                    <img src="images/istockphoto-1165384568-2048x2048.jpg" alt="">
                    <div class="show-country"><p>APPARTEMENT </p></div>
                    </a>
                </div>
                <div class="gallerie-img">
                    <a href="proprietes/villas.php">
                    <img src="images/vil2.jpg" alt="">
                    <div class="show-country"><p>VILLA</p></div>
                    </a>
                </div>
                <div class="gallerie-img">
                    <a href="proprietes/commerces.php">
                    <img src="images/centre commercial.jpg" alt="">
                    <div class="show-country"><p>COMMERCE</p></div>
                    </a>
                </div>
                <div class="gallerie-img">
                    <a href="proprietes/terrains.php">
                    <img src="images\t1.JPG" alt="">
                    <div class="show-country"><p>TERRAIN</p></div>
                    </a>
                </div>

            </div>
    </section> 

<!--  proprietes en ligne -->
    <div class="proprite">
        <div class="hr">
          <p class="tittle-section">PROPIETE RECEMMENT AJOUTEES</p> 
          <hr>
        </div>
        <div class="filter-bar">
          <style>
            .filter-bar{
              margin-bottom: -10px;
            }
          </style>
          <form method="GET" action="">
            <select name="type">
              <option value="">-- Type --</option>
              <option>Appartement</option>
              <option>Villa</option>
              <option>Terrain</option>
              <option>Commerce</option>
            </select>
        
            <select name="option">
              <option value="">-- Vente ou Location --</option>
              <option>Vente</option>
              <option>Location</option>
            </select>
        
            <select name="utilisation">
              <option value="">-- Utilisation --</option>
              <option>Habitation</option>
              <option>Commerce</option>
              <option>Bureau</option>
              <option>Terrain nu</option>
            </select>
        
            <input type="text" name="recherche" placeholder="Titre ou adresse">
            <button type="submit">🔍 Rechercher</button>
          </form>
        </div>
        
        <div class="grid-container">
          <?php foreach ($proprietes as $prop) { ?>
            <div class="propriete-card">
              <img src="proprietes/uploads/<?php echo $prop['image'] ?? 'default.png'; ?>" alt="photo">
              <h3><?php echo htmlspecialchars($prop['titre']); ?></h3>
              <p><?php echo $prop['type']; ?> - <?php echo $prop['superficie']; ?> m²</p>
              <p><strong><?php echo number_format($prop['prix']); ?> FCFA</strong></p>
              <a href="proprietes/details_propriete.php?id=<?php echo $prop['id']; ?>" class="btn">Voir détails</a>
        
        
              <?php 
              if (isset($_SESSION['role']) && $_SESSION['role'] == 'client') { ?>
                <a href="formulaire/ajouter_favori.php?id_propriete=<?php echo $prop['id']; ?>" class="btn favoris-btn">❤️ Favoris</a>
                <a href="rendezvous/prise_rdv.php?id=<?php echo $prop['id']; ?>" class="btn">📅 Prendre rendez-vous</a>
              <?php } elseif (!isset($_SESSION['role'])) { ?>
                <a href="formulaire/auth.php" class="btn">📅 Prendre rendez-vous</a>
              <?php } ?>
        
            </div>
          <?php } ?>
        </div> 
        <div class="ligne">
          <hr>
        </div>
        <div class="vorplus">
          <a href="proprietes/consulter_proprietes.php">VOIR LES AUTRES PROPRIETES ➔</a>
        </div>    
    </div>
   <!-- footer -->
    <footer>
        <p>Immo+</p>
    </footer>

<script>
    /*var menu_toggle = document.querySelector('header .menu-toggle');
    var menu = document.querySelector('header .menu');
    menu_toggle.onclick = function(){
        menu_toggle.classList.toggle('active') ; 
        menu.classList.toggle('responsive') ; 
    }*/
</script>

</body>
</html>