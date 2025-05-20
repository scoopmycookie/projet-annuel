<?php
require_once '../includes/db.php';
session_start();

if ($_SESSION['role'] !== 'admin') exit;

$type = $_GET['type'] ?? '';
$id = (int)($_GET['id'] ?? 0);

if ($type === 'post') {
    $pdo->prepare("DELETE FROM community_comments WHERE post_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM community_posts WHERE id = ?")->execute([$id]);
} elseif ($type === 'comment') {
    $pdo->prepare("DELETE FROM community_comments WHERE id = ?")->execute([$id]);
}

header("Location: community_moderation.php");
exit;
