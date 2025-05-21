<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    http_response_code(403);
    exit('Accès refusé');
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('ID manquant');
}

$invoice_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ? AND company_id = ?");
$stmt->execute([$invoice_id, $_SESSION['company_id']]);
$invoice = $stmt->fetch();

if (!$invoice) {
    http_response_code(404);
    exit('Facture non trouvée ou accès non autorisé');
}

// Rediriger vers la génération de PDF côté admin (ou copier la logique ici)
header("Location: ../admin/generate_pdf.php?id=" . $invoice['id']);
exit;
?>
