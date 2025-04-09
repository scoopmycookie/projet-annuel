<?php
session_start();
require '../database/database.php';

// Vérifie si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Vérifie si un ID est bien passé
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Suppression de l'entreprise
    $stmt = $conn->prepare("DELETE FROM companies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Redirection vers la page de gestion
header("Location: manage_companies.php");
exit();
