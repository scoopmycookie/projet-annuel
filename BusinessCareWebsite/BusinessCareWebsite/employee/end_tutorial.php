<?php
require_once '../includes/db.php';
session_start();

if ($_SESSION['role'] !== 'employee') exit;

$stmt = $pdo->prepare("UPDATE users SET first_login = 0 WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
