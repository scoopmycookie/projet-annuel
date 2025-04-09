<?php
// messages.php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Traitement de réponse au message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_to'])) {
    $reply_to = $_POST['reply_to'];
    $message = $_POST['reply_message'];
    $sender_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    $sender_email = 'fournisseur@businesscare.com'; // optionnel ou récupéré depuis utilisateur
    $sender_role = 'supplier';
    $subject = 'Réponse au message #' . $reply_to;

    $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $sender_name, $sender_email, $subject, $message, $sender_role);
    $stmt->execute();
}

$adminMessages = $conn->query("SELECT * FROM messages WHERE sender_role = 'admin' ORDER BY created_at DESC");
$clientMessages = $conn->query("SELECT * FROM messages WHERE sender_role = 'client' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages reçus</title>
    <link rel="stylesheet" href="../assets/css/providers.css">
    <style>
        h2 {
            margin-top: 40px;
            color: #ff9800;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-container th, .table-container td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
        }
        .table-container th {
            background-color: #222;
            color: #fff;
        }
        .table-container td {
            background-color: #1a1a1a;
        }
        .reply-form {
            margin-top: 10px;
            background: #2a2a2a;
            padding: 10px;
            border-radius: 5px;
        }
        .reply-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #1a1a1a;
            color: white;
        }
        .reply-form button {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #ff9800;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include '../includes/header_providers.php'; ?>

<main class="container">
    <h1>📥 Boîte de réception</h1>

    <section>
        <h2>📨 Messages de l'administrateur</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Répondre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($msg = $adminMessages->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($msg['nom']) ?></td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td><?= htmlspecialchars($msg['sujet']) ?></td>
                            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                            <td>
                                <form method="POST" class="reply-form">
                                    <input type="hidden" name="reply_to" value="<?= $msg['id'] ?>">
                                    <textarea name="reply_message" rows="3" placeholder="Votre réponse..."></textarea>
                                    <button type="submit">Envoyer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h2>👥 Messages des clients</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Répondre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($msg = $clientMessages->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($msg['nom']) ?></td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td><?= htmlspecialchars($msg['sujet']) ?></td>
                            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                            <td>
                                <form method="POST" class="reply-form">
                                    <input type="hidden" name="reply_to" value="<?= $msg['id'] ?>">
                                    <textarea name="reply_message" rows="3" placeholder="Votre réponse..."></textarea>
                                    <button type="submit">Envoyer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include '../includes/footer_providers.php'; ?>
</body>
</html>
