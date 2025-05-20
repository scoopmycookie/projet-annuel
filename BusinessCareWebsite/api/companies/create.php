<?php
// /api/companies/create.php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$required = ['name', 'email', 'siret'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['error' => "Champ requis : $field"]);
        http_response_code(400);
        exit;
    }
}

$stmt = $pdo->prepare("INSERT INTO companies (name, siret, email, phone, website, address_street, address_city, address_postal_code, address_country, representative_name, employees)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->execute([
    $data['name'],
    $data['siret'],
    $data['email'],
    $data['phone'] ?? null,
    $data['website'] ?? null,
    $data['address_street'] ?? null,
    $data['address_city'] ?? null,
    $data['address_postal_code'] ?? null,
    $data['address_country'] ?? null,
    $data['representative_name'] ?? null,
    $data['employees'] ?? 1
]);

echo json_encode(['status' => 'Société ajoutée avec succès']);
