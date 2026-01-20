<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id=?");
    $stmt->execute([$_GET['id']]);
}

header("Location: gestion_utilisateurs.php");
exit();
?>
