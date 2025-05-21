<?php
require_once '../includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $stmt = $pdo->prepare("INSERT INTO community_posts (author, content, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([
        'Admin', 
        $_POST['content']
    ]);
    header("Location: admin_community.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM community_posts WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_community.php");
    exit;
}

$posts = $pdo->query("SELECT * FROM community_posts ORDER BY created_at DESC")->fetchAll();
?>

<style>
    .community-container {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .post {
        border-bottom: 1px solid #ddd;
        padding: 15px 0;
    }
    .post:last-child {
        border-bottom: none;
    }
    .post-author {
        font-weight: bold;
        color: #2c3e50;
    }
    .post-content {
        margin: 5px 0 10px;
    }
    .post-meta {
        font-size: 0.85em;
        color: #999;
    }
    .post-action {
        margin-top: 5px;
    }
    form textarea {
        width: 100%;
        height: 100px;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }
    form button {
        padding: 10px 20px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    form button:hover {
        background: #2980b9;
    }
</style>

<div class="community-container">
    <h2>Espace Communauté (Admin)</h2>

    <form method="post">
        <textarea name="content" placeholder="Ecrire une publication pour la communauté..." required></textarea>
        <button type="submit">Publier</button>
    </form>

    <hr>

    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-author"><?= htmlspecialchars($post['author']) ?></div>
            <div class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
            <div class="post-meta">Publié le <?= $post['created_at'] ?></div>
            <div class="post-action">
                <a href="?delete=<?= $post['id'] ?>" onclick="return confirm('Supprimer cette publication ?')">Supprimer</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
