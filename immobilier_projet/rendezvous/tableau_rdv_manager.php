<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: ../formulaire/auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupère tous les rendez-vous
$stmt = $pdo->prepare("SELECT r.*, 
    p.titre, p.type, p.superficie, p.prix, 
    (SELECT image FROM propriete_images WHERE propriete_id=p.id LIMIT 1) AS image, 
    u.nom, u.prenom, u.telephone,
    a.nom AS agent_nom, a.prenom AS agent_prenom
    FROM rendezvous r 
    JOIN proprietes p ON r.propriete_id = p.id
    JOIN utilisateurs u ON r.client_id = u.id
    LEFT JOIN utilisateurs a ON r.agent_id = a.id
    ORDER BY r.date_demande DESC");
$stmt->execute();
$rdvs = $stmt->fetchAll();

// Récupère la liste des agents
$stmtAgents = $pdo->query("SELECT id, nom, prenom FROM utilisateurs WHERE role='agent'");
$agents = $stmtAgents->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Rendez-vous clients</title>
  <link rel="stylesheet" href="style_rdv.css">
</head>
<body>

<div class="header">
  <h1>📋 Demandes de rendez-vous</h1>
  <div class="button">
    <a href="#" onclick="window.print();" class="btn">🖨️ Imprimer</a>
    <a class="btn" href="../formulaire/tableau_bord_manager.php">⬅ Retour</a>
  </div>
</div>

<div class="grid-container">
  <?php foreach ($rdvs as $rdv) { ?>
    <div class="rdv-card">
      <img src="../proprietes/uploads/<?php echo $rdv['image'] ?? 'default.png'; ?>" alt="photo">
      <h3><?php echo htmlspecialchars($rdv['titre']); ?></h3>
      <p><?php echo $rdv['type']; ?> - <?php echo $rdv['superficie']; ?> m²</p>
      <p><strong><?php echo number_format($rdv['prix']); ?> FCFA</strong></p>
      <p>Client : <?php echo $rdv['nom'].' '.$rdv['prenom']; ?> (<?php echo $rdv['telephone']; ?>)</p>

      <?php if ($rdv['statut'] == 'valide') { ?>
        <p style="color: green;"><strong>📅 Rendez-vous le :</strong> <?php echo date("d/m/Y", strtotime($rdv['date_rdv'])); ?> à <?php echo substr($rdv['heure_rdv'],0,5); ?></p>
        <p>Agent affecté : <?php echo $rdv['agent_nom'] ? $rdv['agent_nom'].' '.$rdv['agent_prenom'] : "Non affecté"; ?></p>
      <?php } else { ?>
        <form method="POST" action="valider_rdv.php">
          <input type="hidden" name="id_rdv" value="<?php echo $rdv['id']; ?>">
          <label>Date :</label>
          <input type="date" name="date_rdv" required>
          <label>Heure :</label>
          <input type="time" name="heure_rdv" required>
          <label>Agent :</label>
          <select name="agent_id" required>
            <option value="">-- Sélectionnez --</option>
            <?php foreach ($agents as $a) { ?>
              <option value="<?php echo $a['id']; ?>"><?php echo $a['nom']." ".$a['prenom']; ?></option>
            <?php } ?>
          </select>
          <button type="submit">Valider</button>
        </form>
      <?php } ?>

      <a href="../proprietes/details_propriete.php?id=<?php echo $rdv['propriete_id']; ?>" class="btn">Détails de la propriété</a>
      <a href="annuler_rdv.php?id=<?php echo $rdv['id']; ?>" id="modif" class="btn" style="background:#c0392b;" onclick="return confirmerAnnulation();">Annuler</a>
      <?php if ($rdv['statut'] == 'valide') { ?>
        <a href="modifier_rdv.php?id=<?php echo $rdv['id']; ?>" id="modif" class="btn" style="background:#f39c12;">Modifier</a>
      <?php } ?>

      <?php if ($rdv['etat_vente'] == 'vendu_agent') { ?>
        <a href="valider_vente_manager.php?id_rdv=<?php echo $rdv['id']; ?>&id_prop=<?php echo $rdv['propriete_id']; ?>" class="btn" style="background:green;">✅ Confirmer vente</a>
      <?php } ?>

      <?php if ($rdv['etat_vente'] == 'confirmee') { ?>
        <style>
          #modif{
            display: none;
          }
        </style>
        <div style="background:#ecf0f1; padding:10px; margin-top:8px; border-radius:5px;">
          <h4 style="color:green;">✅ Vente confirmée</h4>
          <p>Acheteur : <strong><?php echo $rdv['nom'].' '.$rdv['prenom']; ?></strong></p>
          <p>Agent vendeur : <strong><?php echo $rdv['agent_nom'].' '.$rdv['agent_prenom']; ?></strong></p>
          <p>Montant : <strong><?php echo number_format($rdv['prix']); ?> FCFA</strong></p>
          <p>Date RDV : <strong><?php echo date("d/m/Y", strtotime($rdv['date_rdv'])); ?> à <?php echo substr($rdv['heure_rdv'],0,5); ?></strong></p>
        </div>
      <?php } ?>

    </div>
  <?php } ?>
</div>

<script>
function confirmerAnnulation() {
    return confirm("⚠️ Êtes-vous sûr de vouloir annuler ce rendez-vous ?");
}
</script>

</body>
</html>
