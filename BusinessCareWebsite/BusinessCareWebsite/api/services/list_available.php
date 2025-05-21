
<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT id, title, category, service_date, service_time, capacity FROM services WHERE service_date >= ? ORDER BY service_date ASC");
$stmt->execute([$today]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
