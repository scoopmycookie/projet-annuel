<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['sender_id'], $data['recipient_id'], $data['message'])) {
    echo json_encode(['error' => 'Champs requis : sender_id, recipient_id, message']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)");
$stmt->execute([$data['sender_id'], $data['recipient_id'], $data['message']]);

echo json_encode(['status' => 'Message envoyé']);

