<?php
session_start(); // Démarre la session

// Sécurité : accès uniquement aux utilisateurs connectés
if (!isset($_SESSION['id'])) {
    header("Location: auth.php");
    exit();
}

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupère les infos de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch();

// Traitement du formulaire
if (isset($_POST['nom'])) {
    // Récupération des valeurs
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];

    // Si le mot de passe est rempli → on le met à jour
    if (!empty($_POST['motdepasse'])) {
        $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
        $stmtUpdate = $pdo->prepare("UPDATE utilisateurs SET nom=?, prenom=?, telephone=?, email=?, motdepasse=? WHERE id=?");
        $stmtUpdate->execute([$nom, $prenom, $telephone, $email, $motdepasse, $_SESSION['id']]);
    } else {
        $stmtUpdate = $pdo->prepare("UPDATE utilisateurs SET nom=?, prenom=?, telephone=?, email=? WHERE id=?");
        $stmtUpdate->execute([$nom, $prenom, $telephone, $email, $_SESSION['id']]);
    }

    // Traitement de la photo si upload
    if (!empty($_FILES['photo']['name'])) {
        $photo_nom = uniqid() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photo_nom);
        $stmtPhoto = $pdo->prepare("UPDATE utilisateurs SET photo=? WHERE id=?");
        $stmtPhoto->execute([$photo_nom, $_SESSION['id']]);
        $_SESSION['photo'] = $photo_nom;
    }

    // Met à jour les infos en session
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;

    // Retour tableau de bord
    header("Location: tableau_bord_" . $_SESSION['role'] . ".php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier mon profil</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<div class="header">
  <h1>⚙️ Modifier mon profil</h1>
  <a href="tableau_bord_<?php echo $_SESSION['role']; ?>.php" class="btn">⬅ Retour</a>
</div>

<div class="dashboard-container">
  <form action="" method="POST" enctype="multipart/form-data">
    <input type="text" name="nom" value="<?php echo $user['nom']; ?>" required>
    <input type="text" name="prenom" value="<?php echo $user['prenom']; ?>" required>
    <input type="text" name="telephone" value="<?php echo $user['telephone']; ?>" placeholder="Téléphone">
    <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email">

    <input type="password" name="motdepasse" placeholder="Nouveau mot de passe (laisser vide pour ne pas changer)">

    <label>Changer la photo :</label>
    <input type="file" name="photo" accept="image/*">

    <button type="submit">Enregistrer</button>
  </form>
</div>

</body>
</html>
