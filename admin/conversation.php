<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Aucun utilisateur sélectionné.");
}

$user_id = (int) $_GET['id'];

$user_stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    die("Utilisateur introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $nom = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    $email = 'admin@businesscare.com';
    $sujet = 'Message de l’administration';
    $sender_role = 'admin';
    $destinataire_type = 'client'; 

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role, destinataire_id, destinataire_type)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $nom, $email, $sujet, $message, $sender_role, $user_id, $destinataire_type);
        $stmt->execute();
    }
}

$messages_stmt = $conn->prepare("SELECT * FROM messages WHERE 
    (sender_role = 'admin' AND destinataire_id = ?) OR 
    (sender_role != 'admin' AND destinataire_id = ?)
    ORDER BY created_at ASC");
$messages_stmt->bind_param("ii", $user_id, $_SESSION['user_id']);
$messages_stmt->execute();
$messages_result = $messages_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conversation avec <?= htmlspecialchars($user['first_name']) ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .conversation {
            background: #1f1f1f;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            background-color: #2a2a2a;
        }
        .from-admin {
            border-left: 4px solid #ff9800;
        }
        .from-user {
            border-left: 4px solid #2196f3;
        }
        .reply-form textarea {
            width: 100%;
            height: 100px;
            border-radius: 6px;
            border: 1px solid #444;
            padding: 10px;
            background-color: #1f1f1f;
            color: white;
        }
        .reply-form button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #ff9800;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>💬 Conversation avec <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>

    <div class="conversation">
        <?php while ($msg = $messages_result->fetch_assoc()): ?>
            <div class="message <?= $msg['sender_role'] === 'admin' ? 'from-admin' : 'from-user' ?>">
                <strong><?= $msg['sender_role'] === 'admin' ? 'Admin' : htmlspecialchars($msg['nom']) ?></strong><br>
                <?= nl2br(htmlspecialchars($msg['message'])) ?><br>
                <small style="color: #aaa;">Envoyé le <?= date("d/m/Y H:i", strtotime($msg['created_at'])) ?></small>
            </div>
        <?php endwhile; ?>
    </div>

    <form method="POST" class="reply-form">
        <label for="message">Envoyer un message :</label>
        <textarea name="message" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
