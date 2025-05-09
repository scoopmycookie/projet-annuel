<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Prestataire - Business Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <h1>Tableau de bord Prestataire</h1>
    <nav>
        <ul style="display: flex; justify-content: center; gap: 30px; list-style: none; margin: 20px 0;">
            <li><a href="../provider/dashboard.php">Accueil</a></li>
            <li><a href="../provider/services.php">Mes Services</a></li>
            <li><a href="../provider/factures.php">Factures</a></li>
            <li><a href="../provider/payer.php">Paiement</a></li>
            <li><a href="../login/logout.php" style="color: red;">Déconnexion</a></li>
        </ul>
    </nav>
</header>
<main class="form-section">
