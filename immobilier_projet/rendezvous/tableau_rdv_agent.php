<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'agent') {
    header("Location: ../formulaire/auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupère les rendez-vous affectés à cet agent
$stmt = $pdo->prepare("SELECT r.*, 
    p.titre, p.type, p.superficie, p.prix, 
    (SELECT image FROM propriete_images WHERE propriete_id=p.id LIMIT 1) AS image, 
    u.nom, u.prenom, u.telephone
    FROM rendezvous r 
    JOIN proprietes p ON r.propriete_id = p.id
    JOIN utilisateurs u ON r.client_id = u.id
    WHERE r.agent_id = ?
    ORDER BY r.date_rdv DESC");
$stmt->execute([$_SESSION['id']]);
$rdvs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes rendez-vous</title>
  <link rel="stylesheet" href="style_rdv.css">
</head>
<body>

<div class="header">
  <h1>📋 Mes Rendez-vous clients</h1>
  <div class="button">
    <a class="btn" href="../formulaire/tableau_bord_agent.php">⬅ Retour</a>
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
      <p><strong>📅 Date :</strong> <?php echo date("d/m/Y", strtotime($rdv['date_rdv'])); ?> à <?php echo substr($rdv['heure_rdv'],0,5); ?></p>

      <a href="../proprietes/details_propriete.php?id=<?php echo $rdv['propriete_id']; ?>" class="btn">Détails de la propriété</a>
      <a href="messagerie.php?agent=<?php echo $rdv['agent_id']; ?>&client=<?php echo $rdv['client_id']; ?>" class="btn">💬 Messagerie</a>
      <a href="annuler_rdv.php?id=<?php echo $rdv['id']; ?>" id="modif" class="btn" style="background:#c0392b;" onclick="return confirmerAnnulation();">Annuler</a>

      <?php if ($rdv['etat_vente'] == 'en_attente') { ?>
        <a href="valider_vente_agent.php?id_rdv=<?php echo $rdv['id']; ?>" class="btn" style="background:green;">✅ Marquer vendu</a>
      <?php } elseif ($rdv['etat_vente'] == 'vendu_agent') { ?>
        <p style="color:orange; font-weight:bold; margin-top:5px;">Vente signalée, attente confirmation manager</p>
      <?php } elseif ($rdv['etat_vente'] == 'confirmee') { ?>
        <style>
          #modif{
            display: none;
          }
        </style>
        <div style="background:#ecf0f1; padding:8px; margin-top:8px; border-radius:5px;">
          <h4 style="color:green;">✅ Vente confirmée</h4>
          <p>Acheteur : <strong><?php echo $rdv['nom'].' '.$rdv['prenom']; ?></strong></p>
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
