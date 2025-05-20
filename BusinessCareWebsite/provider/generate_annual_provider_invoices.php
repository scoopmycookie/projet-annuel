<?php
require_once '../includes/db.php';

$yearToBill = date('Y') - 1; // facturation pour l'année précédente

$stmt = $pdo->prepare("
    SELECT provider_id, SUM(price) AS total
    FROM services
    WHERE YEAR(service_date) = ?
      AND provider_id IS NOT NULL
    GROUP BY provider_id
");
$stmt->execute([$yearToBill]);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM provider_invoices WHERE provider_id = ? AND year = ?");
    $check->execute([$row['provider_id'], $yearToBill]);
    if ($check->fetchColumn() == 0) {
        $insert = $pdo->prepare("INSERT INTO provider_invoices (provider_id, year, amount) VALUES (?, ?, ?)");
        $insert->execute([$row['provider_id'], $yearToBill, $row['total']]);
    }
}

echo \"Facturation annuelle effectuée pour l'année $yearToBill\\n\";
