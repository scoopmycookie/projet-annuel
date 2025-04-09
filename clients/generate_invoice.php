<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['quote_id'])) {
    die("ID du devis manquant.");
}

$quote_id = intval($_GET['quote_id']);

// Vérifier que le devis appartient bien à l'utilisateur connecté
$check = $conn->prepare("SELECT * FROM quotes WHERE id = ?");
$check->bind_param("i", $quote_id);
$check->execute();
$quote_result = $check->get_result();

if ($quote_result->num_rows === 0) {
    die("Devis introuvable.");
}

$quote = $quote_result->fetch_assoc();

// Vérifier si la facture existe déjà
$existing = $conn->prepare("SELECT id FROM invoices WHERE quote_id = ?");
$existing->bind_param("i", $quote_id);
$existing->execute();
$existing_result = $existing->get_result();

if ($existing_result->num_rows > 0) {
    // Rediriger vers la page facture
    $invoice = $existing_result->fetch_assoc();
    header("Location: view_invoice.php?id=" . $invoice['id']);
    exit();
}

// Générer la facture
$amount = $quote['price_per_employee'];

$insert = $conn->prepare("INSERT INTO invoices (quote_id, user_id, amount) VALUES (?, ?, ?)");
$insert->bind_param("iid", $quote_id, $user_id, $amount);

if ($insert->execute()) {
    $invoice_id = $insert->insert_id;
    header("Location: view_invoice.php?id=$invoice_id");
    exit();
} else {
    die("Erreur lors de la génération de la facture.");
}
?>
