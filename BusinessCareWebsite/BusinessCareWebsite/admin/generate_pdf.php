<?php
ob_clean();
ob_start();
require_once '../includes/db.php';
require_once '../includes/fpdf186/fpdf.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    exit('ID manquant');
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("
    SELECT invoices.*, companies.name AS company_name, companies.email AS company_email, companies.employees
    FROM invoices
    JOIN companies ON invoices.company_id = companies.id
    WHERE invoices.id = ?
");
$stmt->execute([$id]);
$invoice = $stmt->fetch();

if (!$invoice) {
    exit('Facture introuvable');
}

function getPackInfo($employees) {
    if ($employees <= 30) return ['pack' => 'Starter', 'tarif' => 180];
    if ($employees <= 250) return ['pack' => 'Basic', 'tarif' => 150];
    return ['pack' => 'Premium', 'tarif' => 100];
}

$info = getPackInfo($invoice['employees']);
$total = $info['tarif'] * $invoice['employees'];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(40, 40, 40);

// En-tête
$pdf->Cell(0,10, utf8_decode('Business Care - Facture'), 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',14);

// Infos client
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8, utf8_decode("Entreprise : {$invoice['company_name']}"), 0, 1);
$pdf->Cell(0,8, utf8_decode("Email : {$invoice['company_email']}"), 0, 1);
$pdf->Cell(0,8, utf8_decode("Effectif : {$invoice['employees']}"), 0, 1);
$pdf->Cell(0,8, utf8_decode("Pack : {$info['pack']}"), 0, 1);
$pdf->Ln(4);

// Tableau de détails
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,8, utf8_decode("Détail"), 1, 0, 'C');
$pdf->Cell(60,8, utf8_decode("Valeur"), 1, 1, 'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(60,8, utf8_decode("Tarif par salarié"), 1);
$pdf->Cell(60,8, number_format($info['tarif'], 2, ',', ' ') . ' ' . chr(128), 1, 1);

$pdf->Cell(60,8, utf8_decode("Montant total"), 1);
$pdf->Cell(60,8, number_format($total, 2, ',', ' ') . ' ' . chr(128), 1, 1);

$pdf->Cell(60,8, "Statut", 1);
$pdf->Cell(60,8, utf8_decode($invoice['status'] === 'paid' ? 'Payée' : 'Non payée'), 1, 1);

$pdf->Cell(60,8, utf8_decode("Date limite"), 1);
$pdf->Cell(60,8, $invoice['due_date'], 1, 1);

$pdf->Cell(60,8, utf8_decode("Date de création"), 1);
$pdf->Cell(60,8, $invoice['created_at'], 1, 1);

// Pied de page
$pdf->Ln(20);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10, utf8_decode("Document généré automatiquement par Business Care."), 0, 1, 'C');

ob_end_clean(); 
$pdf->Output("I", "Facture.pdf");
exit;

$pdf->Output("I", "Facture_{$invoice['id']}.pdf");
