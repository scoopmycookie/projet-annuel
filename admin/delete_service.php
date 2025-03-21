<?php
session_start();
require '../database/database.php';

if (!isset($_GET['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: manage_services.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_services.php");
exit();
