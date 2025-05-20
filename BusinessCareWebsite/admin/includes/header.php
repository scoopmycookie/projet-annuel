<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Administrateur - Business Care</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<header>
<img src="../assets/img/logo-businesscare.png" alt="Logo Business Care" style="height: 60px;">

    <h1>Espace Administrateur</h1>
    <nav>
        <ul>
            <li><a href="../admin/dashboard.php">Dashboard</a></li>
            <li><a href="../admin/validate_accounts.php">Valider Inscriptions</a></li>
            <li><a href="../admin/messages.php">Messages</a></li>
                        <li><a href="../admin/community_moderation.php">Gestion Modération</a></li>

            <li><a href="../admin/users.php">Utilisateurs</a></li>
            <li><a href="../admin/companies.php">Entreprises</a></li>
            <li><a href="../admin/admin_community.php">communauté</a></li>
            <li><a href="../admin/services.php">Services</a></li>
            <li><a href="../admin/invoices.php">Factures</a></li>
            <li><a href="../admin/quotes.php">Deviss</a></li>
            <li><a href="../login/logout.php" style="color: red;">Déconnexion</a></li>
        </ul>
    </nav>
</header>
<main>
