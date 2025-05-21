<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, name, siret, email FROM companies ORDER BY name ASC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
