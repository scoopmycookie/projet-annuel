<?php
session_start();
require '../database/database.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $company_id = $_GET['id'];
    $action = $_GET['action'];

    // Approuver ou rejeter l'entreprise
    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE companies SET is_verified = 1 WHERE id = ?");
        $stmt->bind_param("i", $company_id);
        if ($stmt->execute()) {
            header("Location: manage_companies.php");
            exit();
        } else {
            echo "Erreur de validation de l'entreprise.";
        }
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE companies SET is_verified = 0 WHERE id = ?");
        $stmt->bind_param("i", $company_id);
        if ($stmt->execute()) {
            header("Location: manage_companies.php");
            exit();
        } else {
            echo "Erreur de mise à jour du statut.";
        }
    }
} else {
    header("Location: manage_companies.php");
    exit();
}
