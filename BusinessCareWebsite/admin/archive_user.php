<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['archive_id'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');

    if ($id && $reason !== '') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'archived', archive_reason = ? WHERE id = ?");
        $stmt->execute([$reason, $id]);
    }
}

header('Location: users.php');
exit;
