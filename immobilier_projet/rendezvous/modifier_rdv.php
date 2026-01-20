<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: ../formulaire/auth.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: tableau_rdv_manager.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupérer rdv
$stmt = $pdo->prepare("SELECT * FROM rendezvous WHERE id=?");
$stmt->execute([$_GET['id']]);
$rdv = $stmt->fetch();

// Récupérer liste des agents
$agents = $pdo->query("SELECT id, nom, prenom FROM utilisateurs WHERE role='agent'")->fetchAll();

// Si formulaire envoyé
if (isset($_POST['date_rdv'], $_POST['heure_rdv'], $_POST['agent_id'])) {
    $stmt = $pdo->prepare("UPDATE rendezvous SET date_rdv=?, heure_rdv=?, agent_id=? WHERE id=?");
    $stmt->execute([$_POST['date_rdv'], $_POST['heure_rdv'], $_POST['agent_id'], $_GET['id']]);

    header("Location: tableau_rdv_manager.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier rendez-vous</title>
  <link rel="stylesheet" href="style_rdv.css">
</head>
<body>

<div class="header">
  <h1>✏️ Modifier rendez-vous</h1>
  <a class="btn" id="btn_modif_rdv" href="tableau_rdv_manager.php">⬅ Retour</a>
</div>

<div style="padding: 30px; max-width: 500px; margin:auto;">
<form id="form" method="POST">
  <label>Date :</label>
  <input type="date" name="date_rdv" value="<?php echo $rdv['date_rdv']; ?>" required><br><br>

  <label>Heure :</label>
  <input type="time" name="heure_rdv" value="<?php echo $rdv['heure_rdv']; ?>" required><br><br>

  <label>Agent :</label>
  <select name="agent_id" required>
    <?php foreach ($agents as $a) { ?>
      <option value="<?php echo $a['id']; ?>" <?php if($a['id']==$rdv['agent_id']) echo 'selected'; ?>>
        <?php echo $a['nom'].' '.$a['prenom']; ?>
      </option>
    <?php } ?>
  </select><br><br>

  <button type="submit" class="btn">Enregistrer</button>
</form>
</div>

</body>
</html>
