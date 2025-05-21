<?php
require_once 'includes/db.php';
require_once 'includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceId = intval($_POST['invoice_id']);
    $stmt = $pdo->prepare("
        SELECT invoices.*, companies.name AS company_name, companies.email AS company_email
        FROM invoices
        JOIN companies ON invoices.company_id = companies.id
        WHERE invoices.id = ?
    ");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch();

    if ($invoice) {
        $body = "<p>Bonjour {$invoice['company_name']},</p>
        <p>Voici votre facture n°{$invoice['id']} d’un montant de {$invoice['amount']} € (statut : {$invoice['status']}).</p>
        <p>Date limite : {$invoice['due_date']}</p>";
        $subject = "Facture #{$invoice['id']} - Business Care";

        if (sendInvoiceMail($invoice['company_email'], $subject, $body)) {
            header("Location: invoices.php?sent=1");
        } else {
            header("Location: invoices.php?sent=0");
        }
    }
}
