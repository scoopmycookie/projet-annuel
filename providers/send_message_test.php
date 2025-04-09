<?php
require '../database/database.php';
$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];
    $sender_role = $_POST['sender_role'];

    $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nom, $email, $sujet, $message, $sender_role);
    $message_sent = $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Envoi Message</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
    <main class="container">
        <h1>📨 Formulaire de test d'envoi de message</h1>

        <?php if ($message_sent): ?>
            <p class="success-msg">Message envoyé avec succès !</p>
        <?php endif; ?>

        <form method="POST">
            <label>Nom</label>
            <input type="text" name="nom" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Sujet</label>
            <input type="text" name="sujet" required>

            <label>Message</label>
            <textarea name="message" rows="5" required></textarea>

            <label>Rôle de l'expéditeur</label>
            <select name="sender_role" required>
                <option value="client">Client</option>
                <option value="admin">Admin</option>
                <option value="employee">Employé</option>
                <option value="supplier">Fournisseur</option>
            </select>

            <button type="submit" class="btn">Envoyer</button>
        </form>
    </main>
</body>
</html>
