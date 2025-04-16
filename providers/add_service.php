<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $supplier_id = $_SESSION['user_id']; 
    
    
    $insert = $conn->prepare("
        INSERT INTO services (title, description, price, start_date, end_date, status, company) 
        VALUES (?, ?, ?, ?, ?, 'en cours', ?)
    ");

    if (!$insert) {
        die("Erreur de préparation : " . $conn->error);
    }

    $insert->bind_param("ssdssi", $title, $description, $price, $start_date, $end_date, $supplier_id);
    $insert->execute();

    header("Location: manage_services.php");
    exit();
}
?>
