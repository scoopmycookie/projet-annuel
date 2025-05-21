<?php

require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['company_id'])) {
    echo json_encode(['error' => 'company_id requis']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("SELECT id, plan, start_date, end_date, amount, status FROM contracts WHERE company_id = ? ORDER BY start_date DESC");
$stmt->execute([$data['company_id']]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
