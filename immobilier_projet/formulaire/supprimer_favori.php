<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client') {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['id_favori'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

    // Vérifie que le favori appartient bien au client
    $stmt = $pdo->prepare("SELECT * FROM favoris WHERE id=? AND client_id=?");
    $stmt->execute([$_GET['id_favori'], $_SESSION['id']]);
    $favori = $stmt->fetch();

    if ($favori) {
        // Supprimer le favori
        $stmt = $pdo->prepare("DELETE FROM favoris WHERE id=?");
        $stmt->execute([$_GET['id_favori']]);
    }
}

header("Location: mes_favoris.php");
exit();
?>
