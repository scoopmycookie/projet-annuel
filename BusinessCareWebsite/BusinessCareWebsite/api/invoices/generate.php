<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['company_id'], $data['amount'])) {
    echo json_encode(['error' => 'Champs requis : company_id, amount']);
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO invoices (company_id, amount, status, due_date, created_at)
    VALUES (?, ?, 'unpaid', DATE_ADD(NOW(), INTERVAL 30 DAY), NOW())
");
$stmt->execute([$data['company_id'], $data['amount']]);

echo json_encode(['status' => 'Facture générée avec succès']);


