<?php
require_once '../includes/db.php';
include 'includes/header.php';

$stmt = $pdo->prepare("
    SELECT u.id, u.name, u.email, MAX(m.created_at) as last_message
    FROM messages m
    JOIN users u ON u.id = IF(m.sender_id != :admin_id, m.sender_id, m.recipient_id)
    WHERE m.sender_id = :admin_id OR m.recipient_id = :admin_id
    GROUP BY u.id, u.name, u.email
    ORDER BY last_message DESC
");
$stmt->execute(['admin_id' => $_SESSION['user_id']]);
$threads = $stmt->fetchAll();

$users = $pdo->query("SELECT id, name, email FROM users WHERE role != 'admin' ORDER BY name ASC")->fetchAll();
?>

<main class="form-section">
    <h2>Messagerie - Conversations</h2>

    <h3>Conversations existantes</h3>
    <?php if (empty($threads)): ?>
        <p>Aucune conversation en cours.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($threads as $conv): ?>
                <li style="margin-bottom: 10px;">
                    <a href="message_thread.php?user_id=<?= $conv['id'] ?>">
                        <strong><?= htmlspecialchars($conv['name']) ?></strong> (<?= htmlspecialchars($conv['email']) ?>)
                        <br>
                        <small style="color: gray;">Dernier message : <?= date('d/m/Y H:i', strtotime($conv['last_message'])) ?></small>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3 style="margin-top: 40px;">Nouvelle conversation</h3>
    <form method="get" action="message_thread.php">
        <label for="user_id">Choisir un utilisateur :</label>
        <select name="user_id" id="user_id" style="width: 100%; padding: 10px; margin: 10px 0;">
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['email']) ?>)</option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="cta-button">Commencer</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
