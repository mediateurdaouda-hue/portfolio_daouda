<?php
session_start(); // Démarre la session

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Vérifie si le formulaire a été soumis
if (isset($_POST['identifiant']) && isset($_POST['motdepasse'])) {

    // Récupère les valeurs
    $identifiant = $_POST['identifiant'];
    $motdepasse = $_POST['motdepasse'];

    // Requête pour récupérer l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE identifiant = ?");
    $stmt->execute([$identifiant]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['etat'] != 'actif') {
            header("Location: auth.php?msg=" . urlencode("Votre compte a été désactivé par un manager."));
            exit();
        }

        // Vérifie le mot de passe
        if (password_verify($motdepasse, $user['motdepasse'])) {

            // Vérifie l'activation si bailleur, agent, manager
            if (in_array($user['role'], ['bailleur', 'agent', 'manager']) && !empty($user['code_confirmation'])) {
                header("Location: auth.php?msg=" . urlencode("Votre compte n'est pas encore activé. Veuillez récupérer votre code auprès d'un manager."));
                exit();
            }

            // Connexion réussie → stocke les infos en session
            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['identifiant'] = $user['identifiant'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['photo'] = $user['photo'];

            // Redirection vers tableau de bord
            header("Location: tableau_bord_" . $user['role'] . ".php");
            exit();

        } else {
            // Mot de passe incorrect
            header("Location: auth.php?msg=" . urlencode("Mot de passe incorrect."));
            exit();
        }

    } else {
        // Identifiant inconnu
        header("Location: auth.php?msg=" . urlencode("Identifiant introuvable."));
        exit();
    }
}
?>
