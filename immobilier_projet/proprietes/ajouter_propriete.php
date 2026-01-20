<?php
session_start();
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], ['bailleur', 'agent', 'manager'])) {
    header("Location: ../formulaire/auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

if (isset($_POST['titre'])) {
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $utilisation = $_POST['utilisation'];
    $option = $_POST['option'];
    $superficie = $_POST['superficie'];
    $nb_pieces = $_POST['nb_pieces'];
    $description = $_POST['description'];
    $adresse = $_POST['adresse'];
    $prix = $_POST['prix'];

    $validation = ($_SESSION['role'] == 'bailleur') ? 'en_attente' : 'validee';

    $stmt = $pdo->prepare("INSERT INTO proprietes (titre, type, utilisation, option_vente_location, superficie, nb_pieces, description, adresse, prix, statut, validation, proprietaire_id)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponible', ?, ?)");
    $stmt->execute([$titre, $type, $utilisation, $option, $superficie, $nb_pieces, $description, $adresse, $prix, $validation, $_SESSION['id']]);

    $propriete_id = $pdo->lastInsertId();

    if (!empty($_FILES['images']['name'][0])) {
    $count = count($_FILES['images']['name']);
    for ($i = 0; $i < $count; $i++) {
        if (!empty($_FILES['images']['name'][$i])) {
            $image_nom = uniqid() . '_' . $_FILES['images']['name'][$i];
            $tmp_name = $_FILES['images']['tmp_name'][$i];
            $target_path = 'uploads/' . $image_nom;

            if (move_uploaded_file($tmp_name, $target_path)) {
                $stmtImg = $pdo->prepare("INSERT INTO propriete_images (propriete_id, image) VALUES (?, ?)");
                $stmtImg->execute([$propriete_id, $image_nom]);
            }
        }
    }
}


    header("Location: consulter_proprietes.php?msg=" . urlencode("Propriété ajoutée avec succès !"));
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une propriété</title>
  <link rel="stylesheet" href="style_proprietes.css">
</head>
<body>

<div class="header">
  <h1>🏠 Ajouter une propriété</h1>
  <a class="btn" href="../formulaire/tableau_bord_<?php echo $_SESSION['role']; ?>.php">⬅ Retour</a>
</div>

<div class="form-container">

  <form action="" method="POST" enctype="multipart/form-data">
    <input type="text" name="titre" placeholder="Titre du bien" required>

    <select name="type" required>
      <option value="">-- Type de propriété --</option>
      <option>Appartement</option>
      <option>Villa</option>
      <option>Terrain</option>
      <option>Commerce</option>
    </select>

    <select name="utilisation" required>
      <option value="">-- Utilisation --</option>
      <option>Habitation</option>
      <option>Commerce</option>
      <option>Bureau</option>
      <option>Terrain nu</option>
    </select>

    <select name="option" required>
      <option value="">-- Vente ou Location --</option>
      <option>Vente</option>
      <option>Location</option>
    </select>

    <input type="number" step="0.01" name="superficie" placeholder="Superficie (m²)" required>
    <input type="number" name="nb_pieces" placeholder="Nombre de pièces" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="text" name="adresse" placeholder="Adresse complète" required>
    <input type="number" name="prix" placeholder="Prix (FCFA)" required>

    <label>Images (plusieurs possibles) :</label>
    <input type="file" name="images[]" accept="image/*" multiple>

    <button type="submit">Ajouter</button>
  </form>

</div>

</body>
</html>
