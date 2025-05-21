<?php
require_once '../includes/db.php';
include 'includes/header.php';

$sender_id = $_SESSION['user_id'];

$stmt = $pdo->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
$admin = $stmt->fetch();
$admin_id = $admin['id'] ?? null;

$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $admin_id) {
    $content = trim($_POST['message']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$sender_id, $admin_id, $content]);
        $message_sent = true;
    }
}

$conv = [];
if ($admin_id) {
    $stmt = $pdo->prepare("SELECT m.*, u.name FROM messages m JOIN users u ON m.sender_id = u.id
                           WHERE (sender_id = :me AND recipient_id = :admin)
                              OR (sender_id = :admin AND recipient_id = :me)
                           ORDER BY m.created_at ASC");
    $stmt->execute(['me' => $sender_id, 'admin' => $admin_id]);
    $conv = $stmt->fetchAll();
}
?>

<main class="form-section">
    <h2>Messagerie avec l'administrateur</h2>

    <div style="margin-bottom: 30px; max-height: 400px; overflow-y: auto; background: #f9f9f9; padding: 15px; border-radius: 6px;">
        <?php if (empty($conv)): ?>
            <p>Aucun message pour le moment.</p>
        <?php else: ?>
            <?php foreach ($conv as $msg): ?>
                <div style="margin-bottom: 10px;">
                    <strong><?= htmlspecialchars($msg['name']) ?>:</strong><br>
                    <span><?= nl2br(htmlspecialchars($msg['message'])) ?></span><br>
                    <small style="color: gray;"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($admin_id): ?>
    <form method="POST">
        <textarea name="message" rows="4" style="width: 100%; padding: 10px;" placeholder="Votre message..." required></textarea>
        <button type="submit" class="cta-button" style="margin-top: 10px;">Envoyer</button>
    </form>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
