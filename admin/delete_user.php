<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /public/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: /admin/manage_users.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de l'utilisateur.";
    }
} else {
    echo "Aucun utilisateur spécifié pour la suppression.";
}
?>
