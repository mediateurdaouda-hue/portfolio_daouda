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
        ORDER BY p.date_ajout DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$proprietes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Propriétés en ligne</title>
  <link rel="stylesheet" href="style_proprietes.css">
</head>
<body>

<div class="header">
  <h1>🏠 Propriétés en ligne</h1>
  <div class="bouton">
    <a class="btn" href="../index.php">Accueil</a>
  <?php if(isset($_SESSION['id'])) { ?>
    <a class="btn" href="../formulaire/tableau_bord_<?php echo $_SESSION['role']; ?>.php">Mon tableau de bord</a>
  <?php } ?>
  </div>
</div>

<div class="filter-bar">
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
      <img src="uploads/<?php echo $prop['image'] ?? 'default.png'; ?>" alt="photo">
      <h3><?php echo htmlspecialchars($prop['titre']); ?></h3>
      <p><?php echo $prop['type']; ?> - <?php echo $prop['superficie']; ?> m²</p>
      <p><strong><?php echo number_format($prop['prix']); ?> FCFA</strong></p>
      <a href="details_propriete.php?id=<?php echo $prop['id']; ?>" class="btn">Voir détails</a>


      <?php 
      if (isset($_SESSION['role']) && $_SESSION['role'] == 'client') { ?>
        <a href="../formulaire/ajouter_favori.php?id_propriete=<?php echo $prop['id']; ?>" class="btn favoris-btn">❤️ Favoris</a>
        <a href="../rendezvous/prise_rdv.php?id=<?php echo $prop['id']; ?>" class="btn">📅 Prendre rendez-vous</a>
      <?php } elseif (!isset($_SESSION['role'])) { ?>
        <a href="../formulaire/auth.php" class="btn">📅 Prendre rendez-vous</a>
      <?php } ?>
      



      <!--<?php //if(isset($_SESSION['id']) && $_SESSION['role'] == 'client') { ?>
          <a href="../formulaire/ajouter_favori.php?id_propriete=<?php //echo $prop['id']; ?>" class="btn favoris-btn">❤️ Favoris</a>
        <a href="../rendezvous/prise_rdv.php?id=<?php //echo $prop['id']; ?>" class="btn">📅 Prendre rendez-vous</a>
      <?php// } ?>-->
    </div>
  <?php } ?>
</div>


</body>
</html>
