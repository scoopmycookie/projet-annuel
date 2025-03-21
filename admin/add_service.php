<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO services (title, description, price, status, created_at, start_date, end_date)
                            VALUES (?, ?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("ssdsss", $title, $description, $price, $status, $start_date, $end_date);
    $stmt->execute();
}

header("Location: manage_services.php");
exit();
