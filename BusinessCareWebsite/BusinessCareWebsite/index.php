<?php
session_start();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en', 'es'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

include_once 'includes/lang.php';
include 'includes/chatbot.php';

function t($key) {
    global $trans;
    return $trans[$key] ?? $key;
}

// Redirection utilisateur connecté
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? '';
    switch ($role) {
        case 'client':
            header('Location: client/dashboard.php');
            break;
        case 'employee':
            header('Location: employee/dashboard.php');
            break;
        case 'provider':
            header('Location: provider/dashboard.php');
            break;
        case 'admin':
            header('Location: admin/dashboard.php');
            break;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <title>Business Care</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div class="header-container">
        <img src="assets/img/logo-businesscare.png" alt="Logo Business Care" class="site-logo">
        <div class="header-content">
            <h1><?= t('BusinessCare') ?></h1>
            <nav>
                <ul>
                    <li><a href="login/login.php">Connexion</a></li>
                    <li><a href="login/register_client.php">Inscription Client</a></li>
                    <li><a href="login/register_provider.php">Inscription Prestataire</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main>
    <form method="get" action="" style="text-align:right; margin: 10px 20px;">
        <select name="lang" onchange="this.form.submit()">
            <option value="fr" <?= ($_SESSION['lang'] ?? 'fr') == 'fr' ? 'selected' : '' ?>>Français</option>
            <option value="en" <?= ($_SESSION['lang'] ?? '') == 'en' ? 'selected' : '' ?>>English</option>
            <option value="es" <?= ($_SESSION['lang'] ?? '') == 'es' ? 'selected' : '' ?>>Español</option>
        </select>
    </form>

    <section class="hero">
        <div class="hero-content">
            <h2><?= t('hero_title') ?></h2>
            <p><?= t('hero_description') ?></p>
            <a href="#devis" class="cta-button"><?= t('quote_button') ?></a>
        </div>
    </section>

    <section id="devis" class="devis-section">
        <h3><?= t('quote_title') ?></h3>
<form action="submit_quotes.php" method="POST" class="quote-form">
    <input type="text" name="company_name" placeholder="Nom de l'entreprise" required>
    <input type="email" name="email" placeholder="Email de contact" required>
    <input type="number" name="amount" step="0.01" placeholder="Montant estimé (€)" required>
    <button type="submit">Envoyer</button>
</form>
        <?php if (isset($_GET['quote']) && $_GET['quote'] === 'success'): ?>
            <p style="color: green; text-align: center; margin-top: 10px;">Votre demande de devis a été envoyée avec succès.</p>
        <?php elseif (isset($_GET['quote']) && $_GET['quote'] === 'error'): ?>
            <p style="color: red; text-align: center; margin-top: 10px;">Erreur lors de l'envoi du devis. Veuillez réessayer.</p>
        <?php endif; ?>
    </section>

    <section class="services-preview">
        <h3><?= t('services_title') ?></h3>
        <ul class="service-list">
            <li>
                <h4><?= t('service1_title') ?></h4>
                <p><?= t('service1_desc') ?></p>
            </li>
            <li>
                <h4><?= t('service2_title') ?></h4>
                <p><?= t('service2_desc') ?></p>
            </li>
            <li>
                <h4><?= t('service3_title') ?></h4>
                <p><?= t('service3_desc') ?></p>
            </li>
        </ul>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
