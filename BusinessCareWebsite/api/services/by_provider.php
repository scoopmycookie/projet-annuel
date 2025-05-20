<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['provider_id'])) {
    echo json_encode(['error' => 'provider_id requis']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("SELECT id, title, service_date, service_time, price FROM services WHERE provider_id = ? ORDER BY service_date DESC");
$stmt->execute([$data['provider_id']]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

