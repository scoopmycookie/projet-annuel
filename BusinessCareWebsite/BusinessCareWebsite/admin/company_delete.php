<?php
require_once '../includes/db.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM companies WHERE id = ?")->execute([$id]);
header("Location: companies.php");
exit;
?>
