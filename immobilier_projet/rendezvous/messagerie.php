<?php
session_start();
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], ['agent', 'client'])) {
    header("Location: ../formulaire/auth.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

if (!isset($_GET['agent']) || !isset($_GET['client'])) {
    $redirect = "../formulaire/tableau_bord_" . $_SESSION['role'] . ".php";
    header("Location: $redirect");
    exit();
}

$agent_id = $_GET['agent'];
$client_id = $_GET['client'];

if ($_SESSION['role'] == 'agent' && $_SESSION['id'] != $agent_id) {
    header("Location: ../formulaire/auth.php");
    exit();
}
if ($_SESSION['role'] == 'client' && $_SESSION['id'] != $client_id) {
    header("Location: ../formulaire/auth.php");
    exit();
}

// Conversation
$stmt = $pdo->prepare("SELECT * FROM conversations WHERE agent_id = ? AND client_id = ?");
$stmt->execute([$agent_id, $client_id]);
$conversation = $stmt->fetch();

if (!$conversation) {
    $stmt = $pdo->prepare("INSERT INTO conversations (agent_id, client_id) VALUES (?, ?)");
    $stmt->execute([$agent_id, $client_id]);
    $conversation_id = $pdo->lastInsertId();
} else {
    $conversation_id = $conversation['id'];
}

// Envoi message
if (isset($_POST['contenu']) && !empty(trim($_POST['contenu']))) {
    $contenu = htmlspecialchars($_POST['contenu']);
    $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, expediteur_id, contenu) VALUES (?, ?, ?)");
    $stmt->execute([$conversation_id, $_SESSION['id'], $contenu]);
    header("Location: messagerie.php?agent=$agent_id&client=$client_id");
    exit();
}

// Récupération messages
$stmt = $pdo->prepare("SELECT * FROM messages WHERE conversation_id = ? ORDER BY date_envoi ASC");
$stmt->execute([$conversation_id]);
$messages = $stmt->fetchAll();

// Nom destinataire
if ($_SESSION['role'] == 'client') {
    $stmtUser = $pdo->prepare("SELECT nom, prenom FROM utilisateurs WHERE id = ?");
    $stmtUser->execute([$agent_id]);
} else {
    $stmtUser = $pdo->prepare("SELECT nom, prenom FROM utilisateurs WHERE id = ?");
    $stmtUser->execute([$client_id]);
}
$destinataire = $stmtUser->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Messagerie privée</title>
  <link rel="stylesheet" href="style_messagerie.css">
</head>
<body>

<div class="header">
  <h1>💬CONVERSATION</h1>
  <div class="bouton">
    <a class="btn" href="../index.php">Accueil</a>
  <?php if(isset($_SESSION['id'])) { ?>
    <a class="btn" href="../formulaire/tableau_bord_<?php echo $_SESSION['role']; ?>.php">Mon tableau de bord</a>
  <?php } ?>
  </div>
</div>
<div class="flex">
<div class="chat-container">
  <div class="chat-header">
    💬 <?php echo htmlspecialchars($destinataire['prenom'] . " " . $destinataire['nom']); ?>
  </div>

  <div class="message-box" id="messageBox">
    <?php foreach ($messages as $msg) { ?>
      <div class="message <?php echo $msg['expediteur_id'] == $_SESSION['id'] ? 'message-agent' : 'message-client'; ?>">
        <p><?php echo nl2br(htmlspecialchars($msg['contenu'])); ?></p>
        <small><?php echo date("d/m/Y H:i", strtotime($msg['date_envoi'])); ?></small>
      </div>
    <?php } ?>
  </div>

  <form method="POST" class="form-message">
    <textarea name="contenu" placeholder="Écrivez..." required></textarea>
    <button type="submit">➤</button>
  </form>
</div>
</div>

<script>
  var messageBox = document.getElementById('messageBox');
  messageBox.scrollTop = messageBox.scrollHeight;
</script>

</body>
</html>
