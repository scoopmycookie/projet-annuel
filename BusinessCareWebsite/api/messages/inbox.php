<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    echo json_encode(['error' => 'user_id requis']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("
    SELECT m.id, m.sender_id, m.recipient_id, m.message, m.created_at, u.name AS sender_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.recipient_id = ?
    ORDER BY m.created_at DESC
");
$stmt->execute([$data['user_id']]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

