<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/login.php');
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header('Location: dashboard.php');
    exit();
}

$provider_id = intval($_GET['id']);
$action = $_GET['action'];

if ($action === 'approve') {
    $stmt = $conn->prepare("UPDATE providers SET is_verified = 1 WHERE id = ?");
    $stmt->bind_param("i", $provider_id);
    $stmt->execute();

    header('Location: dashboard.php?success=fournisseur_valide');
    exit();

} elseif ($action === 'reject') {
    $stmt = $conn->prepare("DELETE FROM providers WHERE id = ?");
    $stmt->bind_param("i", $provider_id);
    $stmt->execute();

    header('Location: dashboard.php?success=fournisseur_supprime');
    exit();
} else {
    header('Location: dashboard.php');
    exit();
}
