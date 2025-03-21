<?php
require '../database/database.php';
$id = $_GET['id'];

$stmt = $conn->prepare("UPDATE companies SET status='banned' WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_companies.php");
exit();
