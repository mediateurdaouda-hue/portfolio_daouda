<?php
session_start(); // Démarre la session

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=gestion_immo', 'root', '');

// Traitement du formulaire
if (
    isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['identifiant']) &&
    isset($_POST['motdepasse']) && isset($_POST['telephone']) && isset($_POST['email']) &&
    isset($_POST['role'])
) {
    // Récupération des valeurs
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $identifiant = $_POST['identifiant'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Gestion de la photo de profil
    $photo_nom = null;
    if (!empty($_FILES['photo']['name'])) {
        $photo_nom = uniqid() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photo_nom);
    }

    // Génération d'un code d'activation pour certains rôles
    $code_activation = null;
    if (in_array($role, ['bailleur', 'agent', 'manager'])) {
        $code_activation = rand(1000, 9999);
    }

    // Insertion en base
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, identifiant, motdepasse, role, photo, code_confirmation, telephone, email)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $identifiant, $motdepasse, $role, $photo_nom, $code_activation, $telephone, $email]);

    // Message personnalisé selon le rôle
    if (in_array($role, ['bailleur', 'agent', 'manager'])) {

        // ENVOIE DE MAIL ...
        $to = $email;
        $subject = "Inscription réussie - Gestion Immo";
        $message = "Bonjour " . $_prenom . ",\n\n";
        $message .= "Merci pour votre inscription.\n";
        $message .= "Veuillez le récupérer auprès d’un manager pour activer votre compte.\n\n";
        $message .= "Merci et bienvenue sur Gestion Immo !";     
        mail($to, $subject, $message);

        $msg = "Votre compte a été créé. Veuillez récupérer votre code d'activation auprès d'un manager.";
    } else {
        $msg = "Inscription réussie !";
    }

    // Redirection avec message
    header("Location: auth.php?msg=" . urlencode($msg));
    exit();
}
?>
