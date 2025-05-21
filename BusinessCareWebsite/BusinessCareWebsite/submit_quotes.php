<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $amount = $_POST['amount'] ?? '';

    if ($company_name && $email && is_numeric($amount)) {
        $stmt = $pdo->prepare("INSERT INTO public_quotes (company_name, email, amount) VALUES (?, ?, ?)");
        $stmt->execute([$company_name, $email, $amount]);

        header("Location: index.php?quote=success");
        exit;
    } else {
        header("Location: index.php?quote=error");
        exit;
    }
}
