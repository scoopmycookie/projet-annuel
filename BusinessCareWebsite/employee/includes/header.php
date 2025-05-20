<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Employé - Business Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
<img src="../assets/img/logo-businesscare.png" alt="Logo Business Care" style="height: 60px;">

    <h1>Espace Employé</h1>
    <nav>
        <ul style="display: flex; justify-content: center; gap: 30px; list-style: none; margin: 20px 0;">
            <li><a href="../employee/dashboard.php">Accueil</a></li>
            <li><a href="../employee/services.php">Mes Services</a></li>
                        <li><a href="../employee/community.php">Communauté</a></li>
            <li><a href="../employee/message.php">Messagerie</a></li>

            <li><a href="../employee/profile.php">Mon Profil</a></li>
            <li><a href="../employee/chatbot.php">Chatbot</a></li>
            <li><a href="../login/logout.php" style="color: red;">Déconnexion</a></li>
        </ul>
    </nav>
</header>
<main class="form-section">