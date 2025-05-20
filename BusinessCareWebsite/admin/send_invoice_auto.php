<?php
require_once '../includes/db.php';
require_once '../includes/fpdf.php';

function sendContractInvoice($invoiceId, $pdo) {
    // Vérifie si la facture a déjà été envoyée
    $check = $pdo->prepare("SELECT sent FROM invoices WHERE id = ?");
    $check->execute([$invoiceId]);
    $alreadySent = $check->fetchColumn();
    if ($alreadySent) return;

    $stmt = $pdo->prepare("
        SELECT invoices.*, companies.name AS company_name, companies.email AS company_email, companies.employees
        FROM invoices
        JOIN companies ON invoices.company_id = companies.id
        WHERE invoices.id = ?
    ");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch();
    if (!$invoice) return;

    $info = getPackInfo($invoice['employees']);
    $total = $info['tarif'] * $invoice['employees'];

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10, utf8_decode("Facture de fin de contrat n° {$invoice['id']}"), 0, 1);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8, utf8_decode("Entreprise : {$invoice['company_name']}"), 0, 1);
    $pdf->Cell(0,8, "Email : {$invoice['company_email']}", 0, 1);
    $pdf->Cell(0,8, utf8_decode("Montant dû : ") . number_format($total, 2, ',', ' ') . ' ' . chr(128), 0, 1);

    $fileName = "Facture_Contrat_{$invoiceId}.pdf";
    $pdf->Output('F', "../pdfs/$fileName");

    // Envoi par email (ou enregistrer pour récupération)
    // ...

    // Marquer comme envoyé
    $pdo->prepare("UPDATE invoices SET sent = 1 WHERE id = ?")->execute([$invoiceId]);
}

function getPackInfo($employees) {
    if ($employees <= 30) return ['pack' => 'Starter', 'tarif' => 180];
    if ($employees <= 250) return ['pack' => 'Basic', 'tarif' => 150];
    return ['pack' => 'Premium', 'tarif' => 100];
}
