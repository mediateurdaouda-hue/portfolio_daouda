<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Propriétés validées
$nb_props_valid = $pdo->query("SELECT COUNT(*) FROM proprietes WHERE validation='validee'")->fetchColumn();

// Propriétés en attente
$nb_props_attente = $pdo->query("SELECT COUNT(*) FROM proprietes WHERE validation='en_attente'")->fetchColumn();

// Nombre de clients
$nb_clients = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='client'")->fetchColumn();

// Nombre d'agents
$nb_agents = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='agent'")->fetchColumn();

// Rendez-vous validés
$nb_rdv_valid = $pdo->query("SELECT COUNT(*) FROM rendezvous WHERE statut='valide'")->fetchColumn();

// Rendez-vous en attente
$nb_rdv_attente = $pdo->query("SELECT COUNT(*) FROM rendezvous WHERE statut='en_attente'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Statistiques Manager</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<div class="header">
  <h1>📊 Statistiques générales</h1>
  <div class="button">
    <a href="#" onclick="window.print();" class="btn">🖨️ Imprimer</a>
    <a class="btn" href="tableau_bord_manager.php">⬅ Retour</a>
  </div>
</div>

<div class="stats-container">
  <div class="stat-box" style="background:#3498db;">
    <h2><?php echo $nb_props_valid; ?></h2>
    <p>Propriétés en ligne</p>
  </div>
  <div class="stat-box" style="background:#f39c12;">
    <h2><?php echo $nb_props_attente; ?></h2>
    <p>Propriétés en attente</p>
  </div>
  <div class="stat-box" style="background:#2ecc71;">
    <h2><?php echo $nb_clients; ?></h2>
    <p>Clients inscrits</p>
  </div>
  <div class="stat-box" style="background:#9b59b6;">
    <h2><?php echo $nb_agents; ?></h2>
    <p>Agents inscrits</p>
  </div>
  <div class="stat-box" style="background:#27ae60;">
    <h2><?php echo $nb_rdv_valid; ?></h2>
    <p>Rendez-vous validés</p>
  </div>
  <div class="stat-box" style="background:#e74c3c;">
    <h2><?php echo $nb_rdv_attente; ?></h2>
    <p>Rendez-vous en attente</p>
  </div>
</div>

</body>
</html>
