<?php
session_start(); // Démarre la session

// Sécurité : seuls agents et managers peuvent valider
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], ['agent', 'manager'])) {
    header("Location: ../formulaire/auth.php");
    exit();
}

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Vérifie si un ID et une action sont passés en GET
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Valide ou refuse la propriété selon l'action
    if ($action == 'valider') {
        $stmt = $pdo->prepare("UPDATE proprietes SET validation = 'validee' WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: proprietes_en_attente.php?msg=" . urlencode("Propriété validée avec succès."));
        exit();

    } elseif ($action == 'refuser') {
        $stmt = $pdo->prepare("UPDATE proprietes SET validation = 'refusee' WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: proprietes_en_attente.php?msg=" . urlencode("Propriété refusée."));
        exit();
    }
}

// Si mauvais appel
header("Location: proprietes_en_attente.php");
exit();
?>
