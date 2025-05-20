<?php
require_once '../includes/db.php';
include 'includes/header.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$posts = $pdo->query("
    SELECT p.id, p.title, p.created_at, u.firstname
    FROM community_posts p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
")->fetchAll();

$comments = $pdo->query("
    SELECT c.id, c.comment, c.created_at, u.firstname, c.post_id
    FROM community_comments c
    JOIN users u ON c.user_id = u.id
    ORDER BY c.created_at DESC
")->fetchAll();
?>

<main class="form-section">
    <h2>Modération de la communauté</h2>

    <h3>Sujets</h3>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <strong><?= htmlspecialchars($post['title']) ?></strong>
                <small>par <?= htmlspecialchars($post['firstname']) ?> - <?= $post['created_at'] ?></small>
                <a href="delete_item.php?type=post&id=<?= $post['id'] ?>" style="color:red;" onclick="return confirm('Supprimer ce sujet ?')">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3 style="margin-top: 40px;">Commentaires</h3>
    <ul>
        <?php foreach ($comments as $c): ?>
            <li>
                <?= htmlspecialchars($c['comment']) ?>
                <small>par <?= htmlspecialchars($c['firstname']) ?> - <?= $c['created_at'] ?></small>
                <a href="delete_item.php?type=comment&id=<?= $c['id'] ?>" style="color:red;" onclick="return confirm('Supprimer ce commentaire ?')">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php include 'includes/footer.php'; ?>
