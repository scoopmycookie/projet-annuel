<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['service_id'])) {
    echo json_encode(['error' => 'service_id requis']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("
    SELECT u.id, u.name, u.firstname, u.email
    FROM service_registrations sr
    JOIN users u ON sr.user_id = u.id
    WHERE sr.service_id = ?
");
$stmt->execute([$data['service_id']]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

