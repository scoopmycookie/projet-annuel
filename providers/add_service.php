<?php
// add_service.php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    $supplier_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
    $stmt->bind_param("i", $supplier_id);
    $stmt->execute();
    $company = $stmt->get_result()->fetch_assoc()['company'];

    $insert = $conn->prepare("INSERT INTO services (title, description, price, start_date, end_date, status, company) VALUES (?, ?, ?, ?, ?, 'en cours', ?)");
    $insert->bind_param("ssdsss", $title, $description, $price, $start_date, $end_date, $company);
    $insert->execute();

    header("Location: manage_services.php");
    exit();
}
?>
