<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client') {
    header("Location: ../proprietes/consulter_proprietes.php");
    exit();
}

if (isset($_GET['id_propriete'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

    $client_id = $_SESSION['id'];
    $propriete_id = $_GET['id_propriete'];

    // Vérifie si déjà en favoris
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE client_id = ? AND propriete_id = ?");
    $stmt->execute([$client_id, $propriete_id]);
    $existe = $stmt->fetchColumn();

    if ($existe == 0) {
        // Ajouter aux favoris
        $stmt = $pdo->prepare("INSERT INTO favoris (client_id, propriete_id) VALUES (?, ?)");
        $stmt->execute([$client_id, $propriete_id]);

        header("Location: ../proprietes/consulter_proprietes.php?msg=" . urlencode("Propriété ajoutée à vos favoris."));
    } else {
        header("Location: ../proprietes/consulter_proprietes.php?msg=" . urlencode("Cette propriété est déjà dans vos favoris."));
    }
    exit();
} else {
    header("Location: ../proprietes/consulter_proprietes.php");
    exit();
}
?>
