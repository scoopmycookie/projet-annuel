<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    echo json_encode(['error' => 'user_id requis']);
    http_response_code(400);
    exit;
}

$fields = ['name', 'firstname', 'email', 'phone', 'position'];
$updates = [];
$params = [];

foreach ($fields as $field) {
    if (isset($data[$field])) {
        $updates[] = "$field = ?";
        $params[] = $data[$field];
    }
}

if (empty($updates)) {
    echo json_encode(['error' => 'Aucun champ à mettre à jour']);
    http_response_code(400);
    exit;
}

$params[] = $data['user_id'];
$sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode(['status' => 'Mise à jour réussie']);

