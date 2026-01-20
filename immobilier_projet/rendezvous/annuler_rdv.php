<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../formulaire/auth.php");
    exit();
}

if (isset($_GET['id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');
    $stmt = $pdo->prepare("UPDATE rendezvous SET statut='annule' WHERE id=?");
    $stmt->execute([$_GET['id']]);
}

if ($_SESSION['role'] == 'manager') {
    header("Location: tableau_rdv_manager.php");
} elseif ($_SESSION['role'] == 'client') {



    // Récupérer l'email du client concerné
    $stmt = $pdo->prepare("SELECT u.email, p.titre FROM rendezvous r
        JOIN utilisateurs u ON r.client_id = u.id
        JOIN proprietes p ON r.propriete_id = p.id
        WHERE r.id = ?");
    $stmt->execute([$_GET['id']]);
    $data = $stmt->fetch();
    if ($data) {
        $to = $data['email'];
        $subject = "Annulation de votre rendez-vous";
        $message = "Bonjour,\n\nVotre rendez-vous pour le bien « " . $data['titre'] . " » a été annulé.\n";
        $message .= "Merci de consulter la plateforme pour reprendre rendez-vous si nécessaire.\n\nGestion Immo.";
    
        mail($to, $subject, $message);
    }

    



    header("Location: tableau_rdv_client.php");
} else {
    header("Location: tableau_rdv_agent.php");
}
exit();
?>
