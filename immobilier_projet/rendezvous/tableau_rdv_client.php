<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client') {
    header("Location: ../formulaire/auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Récupère les rendez-vous du client
$stmt = $pdo->prepare("SELECT r.*, p.titre, p.type, p.superficie, p.prix,
    (SELECT image FROM propriete_images WHERE propriete_id=p.id LIMIT 1) AS image
    FROM rendezvous r
    JOIN proprietes p ON r.propriete_id = p.id
    WHERE r.client_id = ?
    ORDER BY r.date_demande DESC");
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
  <h1>📅 Mes rendez-vous</h1>
  <a class="btn" href="../formulaire/tableau_bord_client.php">⬅ Retour</a>
</div>

<div class="grid-container">
  <?php foreach ($rdvs as $rdv) { ?>
    <div class="rdv-card">
      <img src="../proprietes/uploads/<?php echo $rdv['image'] ?? 'default.png'; ?>" alt="photo">

      <h3><?php echo htmlspecialchars($rdv['titre']); ?></h3>
      <p><?php echo $rdv['type']; ?> - <?php echo $rdv['superficie']; ?> m²</p>
      <p><strong><?php echo number_format($rdv['prix']); ?> FCFA</strong></p>

      <?php if ($rdv['statut'] == 'valide') { ?>
  <p style="color: green;"><strong>📅 Rendez-vous le :</strong> <?php echo date("d/m/Y", strtotime($rdv['date_rdv'])); ?> à <?php echo substr($rdv['heure_rdv'],0,5); ?></p>
  <a href="messagerie.php?agent=<?php echo $rdv['agent_id']; ?>&client=<?php echo $rdv['client_id']; ?>" class="btn">💬 Messagerie</a>
<?php } elseif ($rdv['statut'] == 'en_attente') { ?>
  <p style="color: #d35400;"><strong>En attente de validation</strong></p>
  <a href="annuler_rdv.php?id=<?php echo $rdv['id']; ?>" class="btn" style="background:#c0392b;">Annuler</a>
<?php } elseif ($rdv['statut'] == 'annule') { ?>
  <p style="color: red;"><strong>Rendez-vous annulée</strong></p>
<?php } else { ?>
  <a href="annuler_rdv.php?id=<?php echo $rdv['id']; ?>" 
   class="btn" 
   style="background:#c0392b;" 
   onclick="return confirmerAnnulation();">Annuler</a>

<?php } ?>


      <a href="../proprietes/details_propriete.php?id=<?php echo $rdv['propriete_id']; ?>" class="btn">Détails</a>
      

    </div>
  <?php } ?>
</div>

<script>
// ================================================
// Fonction de confirmation avant d'annuler un rdv
// ================================================
function confirmerAnnulation() {
    return confirm("⚠️ Êtes-vous sûr de vouloir annuler ce rendez-vous ?");
}
</script>


</body>
</html>
