<?php

require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    echo json_encode(['error' => 'user_id requis']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, firstname, email, phone, role, company_id FROM users WHERE id = ?");
$stmt->execute([$data['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(['error' => 'Utilisateur introuvable']);
    http_response_code(404);
}
