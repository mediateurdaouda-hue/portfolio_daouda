<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: ../formulaire/auth.php");
    exit();
}

if (isset($_GET['id_rdv']) && isset($_GET['id_prop'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

    // Confirmer la vente côté rendez-vous
    $stmt = $pdo->prepare("UPDATE rendezvous SET etat_vente = 'confirmee' WHERE id = ?");
    $stmt->execute([$_GET['id_rdv']]);

    // Rendre la propriété indisponible
    $stmt2 = $pdo->prepare("UPDATE proprietes SET statut = 'indisponible' WHERE id = ?");
    $stmt2->execute([$_GET['id_prop']]);
}

header("Location: tableau_rdv_manager.php");
exit();
?>
