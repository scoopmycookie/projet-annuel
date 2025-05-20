<?php
session_start();

?>
<?php include 'chatbot.php'; ?>

<?php
if (!isset($_SESSION)) session_start();

// Détection de la langue via l'URL
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en', 'es'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Langue par défaut
$lang = $_SESSION['lang'] ?? 'fr';

// Charger les traductions
$lang_file = __DIR__ . '/../lang/' . $lang . '.php';
$trans = file_exists($lang_file) ? include($lang_file) : include(__DIR__ . '/../lang/fr.php');
?>
<?php
function t($key) {
    global $trans;
    return $trans[$key] ?? $key;
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Business Care</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
<img src="../assets/img/logo-businesscare.png" alt="Logo Business Care" style="height: 60px;">

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
