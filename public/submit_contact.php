<?php
session_start();
require '../database/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /public/login.php");
    exit();
}

// Initialiser les variables
$error = $success = '';

// Traitement du formulaire de contact
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        $error = "Tous les champs doivent être remplis.";
    } else {
        // Insérer le message dans la base de données
        $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role, destinataire_id, destinataire_type) 
            VALUES (?, ?, ?, ?, 'client', 1, 'admin')");  // `1` ici représente l'ID de l'admin, ajustez-le si nécessaire

        $stmt->bind_param("ssss", $nom, $email, $sujet, $message);

        if ($stmt->execute()) {
            $success = "Votre message a été envoyé avec succès à l'administrateur.";
        } else {
            $error = "Une erreur est survenue lors de l'envoi de votre message. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

<main>
    <section class="hero">
        <div class="container">
            <h1>Contactez-nous</h1>
            <p>Envoyez un message à l'administrateur pour toute question ou demande.</p>
        </div>
    </section>

    <section class="contact-form">
        <div class="container">
            <!-- Affichage des messages d'erreur ou de succès -->
            <?php if ($error): ?>
                <p class="error-msg"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success-msg"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <!-- Formulaire de contact -->
            <form action="submit_contact.php" method="POST">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="sujet">Sujet</label>
                <input type="text" id="sujet" name="sujet" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>

                <button type="submit" class="btn">Envoyer</button>
            </form>
        </div>
    </section>
</main>

<?php include '../includes/footer_public.php'; ?>
</body>
</html>
