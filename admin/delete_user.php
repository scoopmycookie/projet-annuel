<?php
session_start();
require '../database/database.php';

// Vérifiez si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /public/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Préparer la requête pour supprimer l'utilisateur
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Rediriger vers la page des utilisateurs ou une page de confirmation
        header("Location: /admin/manage_users.php");
        exit();
    } else {
        // En cas d'erreur, afficher un message d'erreur
        echo "Erreur lors de la suppression de l'utilisateur.";
    }
} else {
    echo "Aucun utilisateur spécifié pour la suppression.";
}
?>
