<?php
require '../libs/fpdf.php';
require '../database/database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    die("Accès refusé.");
}

$client_id = $_SESSION['user_id'];
$quote_id = $_GET['quote_id'] ?? null;

if (!$quote_id) {
    die("ID de devis manquant.");
}

$stmt = $conn->prepare("SELECT first_name, last_name, company FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    die("Utilisateur introuvable.");
}

$user = $user_result->fetch_assoc();
$company = $user['company'];

$stmt2 = $conn->prepare("SELECT * FROM quotes WHERE id = ? AND company = ?");
$stmt2->bind_param("is", $quote_id, $company);
$stmt2->execute();
$quote_result = $stmt2->get_result();

if ($quote_result->num_rows === 0) {
    die("Devis introuvable pour cette entreprise.");
}

$quote = $quote_result->fetch_assoc();

function encode($text) {
    return iconv("UTF-8", "ISO-8859-1//TRANSLIT", $text);
}


$pdf = new FPDF();
$pdf->AddPage();

$pdf->Image('../assets/images/logo.png', 10, 10, 50); 

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, encode("Facture liée au devis #" . $quote['id'] . " - Business Care"), 0, 1, 'C');
$pdf->Ln(25);

$pdf->SetDrawColor(255, 152, 0);
$pdf->SetLineWidth(0.8);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(10);

// Informations du client
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, encode("Client : " . $user['first_name'] . " " . $user['last_name']), 0, 1);
$pdf->Cell(0, 10, encode("Entreprise : " . $company), 0, 1);
$pdf->Cell(0, 10, encode("Date : " . date("d/m/Y", strtotime($quote['created_at']))), 0, 1);
$pdf->Ln(10);

// Détails du devis
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, encode("Détails du devis"), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(60, 10, encode("Formule :"), 0, 0);
$pdf->Cell(60, 10, encode(ucfirst($quote['plan'])), 0, 1);
$pdf->Cell(60, 10, encode("Montant par salarié :"), 0, 0);
$pdf->Cell(60, 10, encode(number_format($quote['price_per_employee'], 2) . " €"), 0, 1);
$pdf->Ln(20);

$pdf->SetFont('Arial', 'I', 11);
$pdf->Cell(0, 10, encode("Merci pour votre confiance. Pour toute question, contactez notre support."), 0, 1, 'C');

$pdf->Output("I", "Facture_Quote_" . $quote['id'] . ".pdf");
