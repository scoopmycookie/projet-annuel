<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['service_id'])) {
    echo json_encode(['error' => 'service_id requis']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$data['service_id']]);

$service = $stmt->fetch(PDO::FETCH_ASSOC);
if ($service) {
    echo json_encode($service);
} else {
    echo json_encode(['error' => 'Service introuvable']);
    http_response_code(404);
}

