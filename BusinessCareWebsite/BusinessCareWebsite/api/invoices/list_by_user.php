<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    echo json_encode(['error' => 'user_id requis']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = ?");
$stmt->execute([$data['user_id']]);
$user = $stmt->fetch();

if (!$user || !$user['company_id']) {
    echo json_encode(['error' => 'Utilisateur ou société introuvable']);
    http_response_code(404);
    exit;
}

$stmt = $pdo->prepare("SELECT id, amount, status, created_at FROM invoices WHERE company_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['company_id']]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
