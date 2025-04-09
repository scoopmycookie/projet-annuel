<?php
// delete_service.php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID de service manquant.");
}

$delete = $conn->prepare("DELETE FROM services WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

header("Location: manage_services.php");
exit();
?>