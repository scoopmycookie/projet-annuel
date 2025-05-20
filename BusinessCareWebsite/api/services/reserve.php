<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'], $data['service_id'])) {
    echo json_encode(['error' => 'Champs requis : user_id, service_id']);
    http_response_code(400);
    exit;
}

// Vérifie si déjà réservé
$stmt = $pdo->prepare("SELECT COUNT(*) FROM service_registrations WHERE user_id = ? AND service_id = ?");
$stmt->execute([$data['user_id'], $data['service_id']]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(['error' => 'Service déjà réservé']);
    http_response_code(409);
    exit;
}

// Insère la réservation
$stmt = $pdo->prepare("INSERT INTO service_registrations (user_id, service_id) VALUES (?, ?)");
$stmt->execute([$data['user_id'], $data['service_id']]);

echo json_encode(['status' => 'Réservation confirmée']);
