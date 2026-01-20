<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client') {
    header("Location: ../formulaire/auth.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../proprietes/consulter_proprietes.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Vérifie si déjà pris
$stmtCheck = $pdo->prepare("SELECT * FROM rendezvous WHERE client_id=? AND propriete_id=?");
$stmtCheck->execute([$_SESSION['id'], $_GET['id']]);
if ($stmtCheck->fetch()) {
    header("Location: ../proprietes/consulter_proprietes.php?msg=" . urlencode("Rendez-vous déjà demandé."));
    exit();
}

// Enregistre le rdv
$stmt = $pdo->prepare("INSERT INTO rendezvous (client_id, propriete_id) VALUES (?, ?)");
$stmt->execute([$_SESSION['id'], $_GET['id']]);

header("Location: ../rendezvous/tableau_rdv_client.php");
exit();
?>
