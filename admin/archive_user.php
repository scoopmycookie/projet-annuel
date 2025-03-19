<?php
session_start();
require '../database/database.php';

// Activer le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Vérifier si un ID est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Utilisateur non spécifié.");
}

$user_id = $_GET['id'];

// Vérifier si l'utilisateur existe
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Utilisateur introuvable.");
}

// Archiver l'utilisateur (changer le statut en "archived")
$update_stmt = $conn->prepare("UPDATE users SET status = 'archived' WHERE id = ?");
$update_stmt->bind_param("i", $user_id);

if ($update_stmt->execute()) {
    // Redirection après succès
    header("Location: manage_users.php?success=Utilisateur archivé avec succès");
    exit();
} else {
    die("Erreur lors de l'archivage : " . $update_stmt->error);
}
?>
