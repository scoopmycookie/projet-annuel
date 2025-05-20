<?php
session_start();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en', 'es'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

include_once 'includes/lang.php';
include 'includes/chatbot.php';
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
    <div class="header-container">
        <img src="assets/img/logo-businesscare.png" alt="Logo Business Care" class="site-logo">
        <div class="header-content">
            <h1><?= t('BusinessCare') ?></h1>
            <nav>
                <ul>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="login/login.php">Connexion</a></li>
                        <li><a href="login/register_client.php">Inscription Client</a></li>
                        <li><a href="login/register_provider.php">Inscription Prestataire</a></li>
                    <?php else: ?>
                        <?php
                        $role = $_SESSION['role'] ?? '';
                        if ($role === 'client') header('Location: client/dashboard.php');
                        elseif ($role === 'employee') header('Location: employee/dashboard.php');
                        elseif ($role === 'provider') header('Location: provider/dashboard.php');
                        elseif ($role === 'admin') header('Location: admin/dashboard.php');
                        exit;
                        ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main>
    <form method="get" action="">
        <select name="lang" onchange="this.form.submit()">
            <option value="fr" <?= $_SESSION['lang'] == 'fr' ? 'selected' : '' ?>>Français</option>
            <option value="en" <?= $_SESSION['lang'] == 'en' ? 'selected' : '' ?>>English</option>
            <option value="es" <?= $_SESSION['lang'] == 'es' ? 'selected' : '' ?>>Español</option>
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
        <form action="submit_quote.php" method="POST" class="quote-form">
            <input type="text" name="company" placeholder="<?= t('company_placeholder') ?>" required>
            <input type="email" name="email" placeholder="<?= t('email_placeholder') ?>" required>
            <textarea name="details" placeholder="<?= t('details_placeholder') ?>" required></textarea>
            <button type="submit"><?= t('submit_button') ?></button>
        </form>
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
