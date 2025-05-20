<?php
require_once '../includes/db.php';
session_start();

if ($_SESSION['role'] !== 'employee') exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO community_posts (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['title'], $_POST['content']]);
    header("Location: community.php");
    exit;
}
?>
