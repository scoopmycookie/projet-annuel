<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_providers.php?error=ID invalide");
    exit();
}

$provider_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM providers WHERE id = ?");
$stmt->bind_param("i", $provider_id);
$stmt->execute();

header("Location: manage_providers.php?success=Fournisseur supprimé");
exit();
?>
