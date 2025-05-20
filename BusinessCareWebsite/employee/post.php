<?php
require_once '../includes/db.php';
include 'includes/header.php';
session_start();

if ($_SESSION['role'] !== 'employee' || !isset($_GET['id'])) exit;

$post_id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT p.*, u.firstname 
    FROM community_posts p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

$comments = $pdo->prepare("
    SELECT c.comment, c.created_at, u.firstname 
    FROM community_comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.post_id = ?
    ORDER BY c.created_at ASC
");
$comments->execute([$post_id]);
?>

<main class="form-section">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <small>Par <?= htmlspecialchars($post['firstname']) ?> le <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></small>

    <hr>

    <h3>Commentaires</h3>
    <?php foreach ($comments as $comment): ?>
        <div style="margin-bottom:15px; padding:10px; background:#f8f9fa; border-left:4px solid #0077b6;">
            <strong><?= htmlspecialchars($comment['firstname']) ?></strong><br>
            <?= nl2br(htmlspecialchars($comment['comment'])) ?><br>
            <small><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></small>
        </div>
    <?php endforeach; ?>

    <form method="POST" action="add_comment.php" style="margin-top: 20px;">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <textarea name="comment" placeholder="Votre réponse..." required style="width:100%;padding:10px;"></textarea>
        <button type="submit" style="margin-top:10px;">Commenter</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
