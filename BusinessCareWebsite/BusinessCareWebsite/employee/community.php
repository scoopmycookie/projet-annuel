<?php
require_once '../includes/db.php';
include 'includes/header.php';
session_start();

if ($_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}

$posts = $pdo->query("
    SELECT p.id, p.title, p.created_at, u.firstname
    FROM community_posts p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
")->fetchAll();
?>

<main class="form-section">
    <h2>Forum des employés</h2>

    <form method="POST" action="create_post.php" style="margin-bottom: 30px;">
        <input type="text" name="title" placeholder="Titre du sujet" required style="width:100%;padding:10px;margin-bottom:10px;">
        <textarea name="content" placeholder="Exprimez-vous..." required style="width:100%;padding:10px;"></textarea>
        <button type="submit" style="margin-top:10px;">Créer le sujet</button>
    </form>

    <ul style="list-style:none; padding:0;">
        <?php foreach ($posts as $post): ?>
            <li style="padding:15px; border-bottom:1px solid #ddd;">
                <a href="post.php?id=<?= $post['id'] ?>" style="font-size:1.2rem; color:#0077b6; text-decoration:none;">
                    <?= htmlspecialchars($post['title']) ?>
                </a>
                <div style="font-size:0.9rem; color:#555;">Par <?= htmlspecialchars($post['firstname']) ?> le <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></div>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php include 'includes/footer.php'; ?>
