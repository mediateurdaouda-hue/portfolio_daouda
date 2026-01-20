<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupérer tous les utilisateurs avec leur code
$stmt = $pdo->query("SELECT id, nom, prenom, identifiant, role, telephone, code_confirmation, etat
                     FROM utilisateurs 
                     ORDER BY role, nom");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des utilisateurs</title>
  <link rel="stylesheet" href="style_tableaux.css">
</head>
<body>

<div class="header">
  <h1>👥 Gestion des utilisateurs</h1>
  <a class="btn" href="tableau_bord_manager.php">⬅ Retour</a>
</div>

<div class="grid-container">
  <?php foreach ($users as $u) { ?>
    <div class="rdv-card">
      <h3><?php echo $u['nom'].' '.$u['prenom']; ?></h3>
      <p><strong>Identifiant :</strong> <?php echo $u['identifiant']; ?></p>
      <p><strong>Rôle :</strong> <?php echo ucfirst($u['role']); ?></p>
      <p><strong>Téléphone :</strong> <?php echo $u['telephone']; ?></p>

      <?php if ($u['code_confirmation'] != NULL) { ?>
        <p style="color:#c0392b;"><strong>Code activation :</strong> <?php echo $u['code_confirmation']; ?></p>
      <?php } else { ?>
        <p style="color:green;"><strong>Compte activé</strong></p>
      <?php } ?>
      <?php if ($u['role'] != 'client') { ?>
          <p><strong>État :</strong> <?php echo ucfirst($u['etat']); ?></p>
          <a href="changer_etat_utilisateur.php?id=<?php echo $u['id']; ?>&etat=<?php echo $u['etat']; ?>"
             class="btn" style="background:<?php echo ($u['etat'] == 'actif') ? '#c0392b' : '#27ae60'; ?>;">
             <?php echo ($u['etat'] == 'actif') ? 'Désactiver' : 'Activer'; ?>
          </a>
      <?php } ?>

      <!-- Bouton Supprimer -->
      <a href="supprimer_utilisateur.php?id=<?php echo $u['id']; ?>" 
         class="btn" style="background:#c0392b;"
         onclick="return confirm('Supprimer définitivement ce compte ?');">Supprimer</a>
      

      <!-- Bouton Voir propriétés si bailleur -->
      <?php if ($u['role'] == 'bailleur') { ?>
        <a href="../proprietes/proprietes_bailleur.php?id=<?php echo $u['id']; ?>" class="btn" style="background:#2980b9;">Voir ses propriétés</a>
      <?php } ?>

      <!-- Bouton Voir rdv si agent ou client -->
      <?php if (in_array($u['role'], ['agent','client'])) { ?>
        <a href="../rendezvous/rdv_utilisateur.php?id=<?php echo $u['id']; ?>" class="btn" style="background:#27ae60;">Voir ses rendez-vous</a>
      <?php } ?>

      

    </div>
  <?php } ?>
</div>

</body>
</html>
