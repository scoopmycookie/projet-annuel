<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO companies (name, email, phone, address, status, created_at) VALUES (?, ?, ?, ?, 'active', NOW())");
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    $stmt->execute();
}

header("Location: manage_companies.php");
exit();
