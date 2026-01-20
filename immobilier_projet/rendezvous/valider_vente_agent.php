<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'agent') {
    header("Location: ../formulaire/auth.php");
    exit();
}

if (isset($_GET['id_rdv'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

    $stmt = $pdo->prepare("UPDATE rendezvous SET etat_vente = 'vendu_agent' WHERE id = ?");
    $stmt->execute([$_GET['id_rdv']]);
}

header("Location: tableau_rdv_agent.php");
exit();
?>
