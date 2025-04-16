<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID du service manquant.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $update = $conn->prepare("UPDATE services SET title = ?, description = ?, price = ?, start_date = ?, end_date = ? WHERE id = ?");
    $update->bind_param("ssdssi", $title, $description, $price, $start_date, $end_date, $id);
    $update->execute();

    header("Location: manage_services.php");
    exit();
}

$service = $conn->prepare("SELECT * FROM services WHERE id = ?");
$service->bind_param("i", $id);
$service->execute();
$data = $service->get_result()->fetch_assoc();
?>
<head>
    <meta charset="UTF-8">
    <title>Mes Services</title>
    <link rel="stylesheet" href="../assets/css/providers.css">
</head>
<form action="" method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" required>
    <textarea name="description" required><?= htmlspecialchars($data['description']) ?></textarea>
    <input type="number" name="price" value="<?= htmlspecialchars($data['price']) ?>" required>
    <input type="date" name="start_date" value="<?= htmlspecialchars($data['start_date']) ?>" required>
    <input type="date" name="end_date" value="<?= htmlspecialchars($data['end_date']) ?>" required>
    <button type="submit" class="btn">Mettre à jour</button>
</form>