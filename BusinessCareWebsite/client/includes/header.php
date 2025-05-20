<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Client - Business Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
    <h1>Espace Client</h1>
    <nav>
        <ul style="display: flex; justify-content: center; gap: 30px; list-style: none; margin: 20px 0;">
            <li><a href="../client/dashboard.php">Tableau de bord</a></li>
            <li><a href="../client/invoices.php">Mes Factures</a></li>
            <li><a href="../client/employes.php">Mes Employés</a></li>
            <li><a href="../client/message.php">Messagee</a></li>

            <li><a href="../client/payer.php">Paiement</a></li>

            <li><a href="../login/logout.php" style="color: red;">Déconnexion</a></li>
        </ul>
    </nav>
</header>
<main class="form-section">