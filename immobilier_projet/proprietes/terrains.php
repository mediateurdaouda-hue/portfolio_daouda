<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Condition : Terrains uniquement
$condition = "WHERE type = 'Terrain' AND validation='validee'";

// Filtres optionnels
if (!empty($_GET['utilisation'])) {
    $condition .= " AND utilisation = '" . $_GET['utilisation'] . "'";
}
if (!empty($_GET['option'])) {
    $condition .= " AND option_vente_location = '" . $_GET['option'] . "'";
}
if (!empty($_GET['prix_min'])) {
    $condition .= " AND prix >= " . $_GET['prix_min'];
}
if (!empty($_GET['prix_max'])) {
    $condition .= " AND prix <= " . $_GET['prix_max'];
}

// Récupération des terrains
$stmt = $pdo->prepare("SELECT p.*, (SELECT image FROM propriete_images WHERE propriete_id=p.id LIMIT 1) AS image
                       FROM proprietes p $condition ORDER BY p.id DESC");
$stmt->execute();
$proprietes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Terrains</title>
  <link rel="stylesheet" href="style_proprietes.css">
</head>
<body>

<div class="header">
  <h1>🗺️ Terrains disponibles</h1>
  <div class="bouton">
    <a class="btn" href="../index.php">Accueil</a>
    <a href="../formulaire/tableau_bord_client.php" class="btn">⬅ Retour</a>
  </div>
</div>

<div class="filter-bar">
  <form method="GET">
    <select name="utilisation">
      <option value="">Utilisation</option>
      <option value="Habitation">Habitation</option>
      <option value="Commerce">Commerce</option>
      <option value="Bureau">Bureau</option>
      <option value="Terrain nu">Terrain nu</option>
    </select>

    <select name="option">
      <option value="">Vente ou Location</option>
      <option value="Vente">Vente</option>
      <option value="Location">Location</option>
    </select>

    <input type="number" name="prix_min" placeholder="Prix min">
    <input type="number" name="prix_max" placeholder="Prix max">

    <button type="submit">Rechercher</button>
  </form>
</div>

<div class="grid-container">
<?php
if (count($proprietes) == 0) {
    echo "<p style='text-align:center;'>Aucun terrain trouvé.</p>";
} else {
    foreach ($proprietes as $prop) {
        echo "<div class='propriete-card'>
                <img src='uploads/" . ($prop['image'] ?? "default.png") . "'>
                <h3>{$prop['titre']}</h3>
                <p><strong>Superficie :</strong> {$prop['superficie']} m²</p>
                <p><strong>Prix :</strong> " . number_format($prop['prix']) . " FCFA</p>
                <a href='details_propriete.php?id={$prop['id']}' class='btn'>Détails</a>";
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'client') { ?>
                  <a href="../formulaire/prise_rdv.php?id_propriete=<?php echo $prop['id']; ?>" class="btn">📅 Prendre rendez-vous</a>
                <?php } elseif (!isset($_SESSION['role'])) { ?>
                  <a href="../formulaire/auth.php" class="btn">📅 Prendre rendez-vous</a>
                <?php } ?>

        <?php    
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'client') {
            echo "<a href='../formulaire/ajouter_favori.php?id_propriete={$prop['id']}' class='btn favoris-btn'>❤️ Favoris</a>";
        }
        echo "</div>";
    }
}
?>
</div>

</body>
</html>
