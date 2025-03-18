<?php
session_start(); // Démarrer la session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Care - Bien-être en entreprise</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo-container">
        <img src="../images/logo.png" alt="Business Care" style="height: 90px !important; width: auto !important;">
        </div>
        <div class="header-text">
            <h1>Business Care</h1>
            <p>Améliorer le bien-être et la cohésion en entreprise</p>
        </div>
    </div>
</header>

<nav>
    <div class="nav-links">
        <a href="index.php">Accueil</a>
        <a href="services.php">Services</a>
        <a href="salaries.php">Salariés</a>
        <a href="prestataires.php">Prestataires</a>
        <a href="contact.php">Contact</a>
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") : ?>
            <a href="admin/dashboard_admin.php">Admin</a>
        <?php endif; ?>
    </div>
    <div class="nav-auth">
        <?php if (isset($_SESSION["user"])) : ?>
            <a href="auth/logout.php" class="btn-login">Se déconnecter</a>
        <?php else : ?>
            <a href="login.php" class="btn-login">Se connecter</a>
        <?php endif; ?>
    </div>
</nav>
