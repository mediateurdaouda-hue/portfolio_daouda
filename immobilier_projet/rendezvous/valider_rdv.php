<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'manager') {
    header("Location: ../formulaire/auth.php");
    exit();
}

if (isset($_POST['id_rdv']) && isset($_POST['date_rdv']) && isset($_POST['heure_rdv']) && isset($_POST['agent_id'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

    // Mettre à jour le rendez-vous
    $stmt = $pdo->prepare("UPDATE rendezvous SET 
        date_rdv = ?, 
        heure_rdv = ?, 
        agent_id = ?, 
        statut = 'valide'
        WHERE id = ?");
    $stmt->execute([$_POST['date_rdv'], $_POST['heure_rdv'], $_POST['agent_id'], $_POST['id_rdv']]);

    // Récupérer l'email du client concerné
    $stmt = $pdo->prepare("SELECT u.email, p.titre FROM rendezvous r
        JOIN utilisateurs u ON r.client_id = u.id
        JOIN proprietes p ON r.propriete_id = p.id
        WHERE r.id = ?");
    $stmt->execute([$_POST['id_rdv']]);
    $data = $stmt->fetch();

    if ($data) {
        $to = $data['email'];
        $subject = "Confirmation de votre rendez-vous";
        $message = "Bonjour,\n\nVotre rendez-vous pour le bien « " . $data['titre'] . " » a été validé.\n";
        $message .= "Date : " . $_POST['date_rdv'] . "\n";
        $message .= "Heure : " . $_POST['heure_rdv'] . "\n\n";
        $message .= "Merci de vous présenter à l'heure prévue.\n\nGestion Immo.";

        // Envoyer l'email
        mail($to, $subject, $message);
    }
}
// Récupérer l'email de l'agent affecté
$stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
$stmt->execute([$_POST['agent_id']]);
$agent = $stmt->fetch();

if ($agent) {
    $to_agent = $agent['email'];
    $subject_agent = "Vous avez un rendez-vous à gérer";
    $message_agent = "Bonjour,\n\nUn rendez-vous vous a été affecté pour le bien « " . $data['titre'] . " ».\n";
    $message_agent .= "Date : " . $_POST['date_rdv'] . " à " . $_POST['heure_rdv'] . "\n\n";
    $message_agent .= "Connectez-vous à votre tableau de bord pour le consulter.\n\nGestion Immo.";

    mail($to_agent, $subject_agent, $message_agent);
}


header("Location: tableau_rdv_manager.php");
exit();
?>
