<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$users = $conn->query("SELECT id, first_name, last_name, email, role FROM users WHERE role IN ('client', 'supplier') ORDER BY role, last_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_id = $_POST['recipient_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (user_id, subject, message, sender_role, created_at) VALUES (?, ?, ?, 'admin', NOW())");
    $stmt->bind_param("iss", $recipient_id, $subject, $message);

    if ($stmt->execute()) {
        $success = "Message envoyé avec succès.";
    } else {
        $error = "Erreur lors de l'envoi du message.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Envoyer un message - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📨 Envoyer un message</h1>

    <?php if (isset($success)) echo "<p class='success-msg'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

    <form method="POST">
        <label for="recipient_id">Destinataire :</label>
        <select name="recipient_id" required>
            <option value="">-- Choisir un utilisateur --</option>
            <?php while ($user = $users->fetch_assoc()): ?>
                <option value="<?= $user['id'] ?>">
                    <?= ucfirst($user['role']) ?> - <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?> (<?= $user['email'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label for="subject">Sujet :</label>
        <input type="text" name="subject" required>

        <label for="message">Message :</label>
        <textarea name="message" rows="6" required></textarea>

        <button type="submit" class="btn">Envoyer</button>
    </form>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
