<?php

require_once '../../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'], $data['password'])) {
    echo json_encode(['error' => 'Email et mot de passe requis']);
    http_response_code(400);
    exit;
}

$email = $data['email'];
$password = $data['password'];

$stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode([
        'status' => 'success',
        'user_id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role']
    ]);
} else {
    echo json_encode(['error' => 'Identifiants invalides']);
    http_response_code(401);
}
