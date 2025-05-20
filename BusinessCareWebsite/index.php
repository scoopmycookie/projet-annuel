<?php
session_start();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en', 'es'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'fr';

include_once 'includes/lang.php';
include 'includes/header.php';
?>

<form method="get" action="">
    <select name="lang" onchange="this.form.submit()">
        <option value="fr" <?= $lang == 'fr' ? 'selected' : '' ?>>Français</option>
        <option value="en" <?= $lang == 'en' ? 'selected' : '' ?>>English</option>
        <option value="es" <?= $lang == 'es' ? 'selected' : '' ?>>Español</option>
    </select>
</form>

<!-- Section Hero -->
<section class="hero">
    <div class="hero-content">
        <h2><?= $trans['hero_title'] ?></h2>
        <p><?= $trans['hero_description'] ?></p>
        <a href="#devis" class="cta-button"><?= $trans['quote_button'] ?></a>
    </div>
</section>

<!-- Section Devis -->
<section id="devis" class="devis-section">
    <h3><?= $trans['quote_title'] ?></h3>
    <form action="submit_quote.php" method="POST" class="quote-form">
        <input type="text" name="company" placeholder="<?= $trans['company_placeholder'] ?>" required>
        <input type="email" name="email" placeholder="<?= $trans['email_placeholder'] ?>" required>
        <textarea name="details" placeholder="<?= $trans['details_placeholder'] ?>" required></textarea>
        <button type="submit"><?= $trans['submit_button'] ?></button>
    </form>
</section>

<!-- Section Services -->
<section class="services-preview">
    <h3><?= $trans['services_title'] ?></h3>
    <ul class="service-list">
        <li>
            <h4><?= $trans['service1_title'] ?></h4>
            <p><?= $trans['service1_desc'] ?></p>
        </li>
        <li>
            <h4><?= $trans['service2_title'] ?></h4>
            <p><?= $trans['service2_desc'] ?></p>
        </li>
        <li>
            <h4><?= $trans['service3_title'] ?></h4>
            <p><?= $trans['service3_desc'] ?></p>
        </li>
    </ul>
</section>

<?php include 'includes/footer.php'; ?>
