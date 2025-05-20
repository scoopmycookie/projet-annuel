<?php
require_once '../includes/db.php';
include 'includes/header.php';

$admin_id = $_SESSION['user_id'];
$other_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$other_id]);
$other_user = $stmt->fetch();

if (!$other_user) {
    echo "<p style='color:red;'>Utilisateur introuvable.</p>";
    include 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, message, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$admin_id, $other_id, trim($_POST['message'])]);
    header("Location: message_thread.php?user_id=" . $other_id);
    exit;
}

$stmt = $pdo->prepare("
    SELECT m.*, u.name FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE (sender_id = :admin AND recipient_id = :other)
       OR (sender_id = :other AND recipient_id = :admin)
    ORDER BY m.created_at ASC
");
$stmt->execute(['admin' => $admin_id, 'other' => $other_id]);
$messages = $stmt->fetchAll();
?>

<main class="form-section">
    <h2>Conversation avec <?= htmlspecialchars($other_user['name']) ?></h2>

    <div style="margin-bottom: 30px; max-height: 400px; overflow-y: auto; background: #f9f9f9; padding: 15px; border-radius: 6px;">
        <?php if (empty($messages)): ?>
            <p>Aucun message échangé.</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div style="margin-bottom: 10px;">
                    <strong><?= htmlspecialchars($msg['name']) ?>:</strong><br>
                    <span><?= nl2br(htmlspecialchars($msg['message'])) ?></span><br>
                    <small style="color: gray;"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form method="POST">
        <textarea name="message" rows="4" style="width: 100%; padding: 10px;" placeholder="Votre réponse..." required></textarea>
        <button type="submit" class="cta-button" style="margin-top: 10px;">Envoyer</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
