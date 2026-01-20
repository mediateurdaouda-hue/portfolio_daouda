<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupérer la liste des agents
$stmtAgents = $pdo->query("SELECT id, nom, prenom FROM utilisateurs WHERE role='agent'");
$agents = $stmtAgents->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Statistiques des agents</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<div class="header">
  <h1>📊 Statistiques des agents</h1>
  <div class="button">
    <a href="#" onclick="window.print();" class="btn">🖨️ Imprimer</a>
    <a class="btn" href="tableau_bord_manager.php">⬅ Retour</a>
  </div>
</div>

<div class="stats-container">
<?php
if (count($agents) == 0) {
    echo "<p style='text-align:center;'>Aucun agent enregistré.</p>";
} else {
    foreach ($agents as $a) {
        $id = $a['id'];

        // Compter total des rendez-vous affectés à l'agent
        $total = $pdo->query("SELECT COUNT(*) FROM rendezvous WHERE agent_id = $id")->fetchColumn();

        // Compter rendez-vous validés
        $valides = $pdo->query("SELECT COUNT(*) FROM rendezvous WHERE agent_id = $id AND statut='valide'")->fetchColumn();

        // Compter rendez-vous en attente
        $attente = $pdo->query("SELECT COUNT(*) FROM rendezvous WHERE agent_id = $id AND statut='en_attente'")->fetchColumn();
?>
    <div class="stat-box" style="background:#2980b9;">
      <h3><?php echo $a['nom'].' '.$a['prenom']; ?></h3>
      <p><strong>Total RDV :</strong> <?php echo $total; ?></p>
      <p><strong>Validés :</strong> <?php echo $valides; ?></p>
      <p><strong>En attente :</strong> <?php echo $attente; ?></p>
    </div>
<?php
    }
}
?>
</div>

</body>
</html>
