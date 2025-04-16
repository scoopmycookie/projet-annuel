<?php
session_start();
require '../database/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Utilisateur non spécifié.");
}

$user_id = $_GET['id'];

$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Utilisateur introuvable.");
}

$update_stmt = $conn->prepare("UPDATE users SET status = 'banned' WHERE id = ?");
$update_stmt->bind_param("i", $user_id);

if ($update_stmt->execute()) {
    header("Location: manage_users.php?success=Utilisateur banni avec succès");
    exit();
} else {
    die("Erreur lors du bannissement : " . $update_stmt->error);
}
?>
