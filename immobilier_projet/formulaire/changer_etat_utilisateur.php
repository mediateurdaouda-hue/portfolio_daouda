<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['etat'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');
    $newEtat = ($_GET['etat'] == 'actif') ? 'inactif' : 'actif';

    $stmt = $pdo->prepare("UPDATE utilisateurs SET etat=? WHERE id=?");
    $stmt->execute([$newEtat, $_GET['id']]);
}

header("Location: gestion_utilisateurs.php");
exit();
?>
