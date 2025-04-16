<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$messages = $conn->query("SELECT * FROM messages WHERE sender_role = 'client' AND destinataire_type = 'admin' ORDER BY created_at DESC");

$users = $conn->query("SELECT id, first_name, last_name, role FROM users ORDER BY role, last_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $reply_message = $_POST['reply_message'];
    $original_message_id = $_POST['original_message_id'];
    $sender_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    $sender_email = 'admin@businesscare.com';
    $sender_role = 'admin';
    $subject = 'Réponse à votre message';

    $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role, destinataire_id, destinataire_type) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $sender_name, $sender_email, $subject, $reply_message, $sender_role, $original_message_id, $sender_role);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $receiver_id = $_POST['receiver_id'];
    $receiver_role = $_POST['receiver_role'];
    $subject = $_POST['subject'];
    $message_text = $_POST['message'];

    $admin_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    $admin_email = 'admin@businesscare.com';

    $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role, destinataire_id, destinataire_type)
                            VALUES (?, ?, ?, ?, 'admin', ?, ?)");
    $stmt->bind_param("ssssss", $admin_name, $admin_email, $subject, $message_text, $receiver_id, $receiver_role);
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
        .reply-form textarea, .form-container textarea {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
            background: #1a1a1a;
            border: 1px solid #444;
            color: #fff;
            border-radius: 4px;
        }
        .reply-form button, .form-container button {
            margin-top: 5px;
            padding: 6px 12px;
            background: #ff9800;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px;
            border-radius: 4px;
            border: 1px solid #444;
            background: #1a1a1a;
            color: white;
        }
    </style>
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📨 Gestion des messages</h1>

    <section class="form-container">
        <h2>✉️ Envoyer un message</h2>
        <form method="POST">
            <label for="receiver_id">Destinataire :</label>
            <select name="receiver_id" id="receiver_id" required>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <option value="<?= $user['id'] ?>" data-role="<?= $user['role'] ?>">
                        <?= ucfirst($user['role']) ?> - <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="hidden" name="receiver_role" id="receiver_role">

            <label for="subject">Sujet :</label>
            <input type="text" name="subject" required>

            <label for="message">Message :</label>
            <textarea name="message" rows="4" required></textarea>

            <button type="submit" name="send_message">Envoyer</button>
        </form>
    </section>

    <div class="table-container">
        <h2>📥 Messages reçus des clients</h2>
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

<script>
    const select = document.getElementById('receiver_id');
    const roleInput = document.getElementById('receiver_role');

    select.addEventListener('change', () => {
        const selected = select.options[select.selectedIndex];
        roleInput.value = selected.dataset.role;
    });

    window.addEventListener('DOMContentLoaded', () => {
        const selected = select.options[select.selectedIndex];
        if (selected) roleInput.value = selected.dataset.role;
    });
</script>
</body>
</html>
