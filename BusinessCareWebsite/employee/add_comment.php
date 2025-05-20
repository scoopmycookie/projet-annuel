<?php
require_once '../includes/db.php';
session_start();

if ($_SESSION['role'] !== 'employee') exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO community_comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['post_id'], $_SESSION['user_id'], $_POST['comment']]);
    header("Location: post.php?id=" . $_POST['post_id']);
    exit;
}
?>
