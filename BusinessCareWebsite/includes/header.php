<?php
session_start();

?>
<?php include 'chatbot.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Business Care</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <h1>Bienvenue chez Business Care</h1>
    <nav>
        <ul>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li><a href="login/login.php">Connexion</a></li>
                <li><a href="login/register_client.php">Inscription Client</a></li>
                <li><a href="login/register_provider.php">Inscription Prestataire</a></li>
            <?php else: ?>
                <?php
                    $role = $_SESSION['role'] ?? '';
                    if ($role === 'client') {
                        header('Location: client/dashboard.php');
                        exit;
                    } elseif ($role === 'employee') {
                        header('Location: employee/dashboard.php');
                        exit;
                    } elseif ($role === 'provider') {
                        header('Location: provider/dashboard.php');
                        exit;
                    } elseif ($role === 'admin') {
                        header('Location: admin/dashboard.php');
                        exit;
                    }
                ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
