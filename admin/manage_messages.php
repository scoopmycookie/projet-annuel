<?php
session_start();
require '../database/database.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Récupérer les messages envoyés par les clients (modification pour récupérer les messages envoyés par les clients à l'admin)
$messages = $conn->query("SELECT * FROM messages WHERE sender_role = 'client' AND destinataire_type = 'admin' ORDER BY created_at DESC");

// Traitement de la réponse à un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $reply_message = $_POST['reply_message'];
    $original_message_id = $_POST['original_message_id'];
    $sender_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    $sender_email = 'admin@businesscare.com'; // Remplacer par l'email de l'admin
    $sender_role = 'admin';
    $subject = 'Réponse à votre message';

    // Insérer la réponse dans la base de données
    $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role, destinataire_id, destinataire_type) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $sender_name, $sender_email, $subject, $reply_message, $sender_role, $original_message_id, 'client');
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des messages</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .reply-form textarea {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
            background: #1a1a1a;
            border: 1px solid #444;
            color: #fff;
            border-radius: 4px;
        }
        .reply-form button {
            margin-top: 5px;
            padding: 6px 12px;
            background: #ff9800;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📨 Gestion des messages</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Reçu le</th>
                    <th>Répondre</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($msg['nom']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td><?= htmlspecialchars($msg['sujet']) ?></td>
                        <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($msg['created_at'])) ?></td>
                        <td>
                            <!-- Formulaire pour répondre au message -->
                            <form method="POST" class="reply-form">
                                <input type="hidden" name="original_message_id" value="<?= $msg['id'] ?>">
                                <textarea name="reply_message" rows="3" placeholder="Votre réponse..." required></textarea>
                                <button type="submit">Envoyer</button>
                            </form>
                        </td>
                        <td>
                            <a href="delete_message.php?id=<?= $msg['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer ce message ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
