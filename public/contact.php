<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

<main>
    <section class="hero">
        <div class="container">
            <h1>Contactez-nous</h1>
            <p>Une question ? Besoin d'informations ? Remplissez le formulaire ci-dessous, nous vous répondrons rapidement.</p>
        </div>
    </section>

    <section class="contact-form">
        <div class="container">

            <!-- Messages de retour -->
            <?php if (isset($_SESSION['contact_success'])): ?>
                <p class="success-msg"><?= $_SESSION['contact_success']; unset($_SESSION['contact_success']); ?></p>
            <?php endif; ?>

            <?php if (isset($_SESSION['contact_error'])): ?>
                <p class="error-msg"><?= $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?></p>
            <?php endif; ?>

            <form action="submit_contact.php" method="POST">
                <label for="name">Nom et Prénom</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Sujet</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit" class="btn">Envoyer le message</button>
            </form>
        </div>
    </section>

    <section class="contact-info">
        <div class="container">
            <h2>Nos Coordonnées</h2>
            <p><strong>Email :</strong> contact@businesscare.com</p>
            <p><strong>Téléphone :</strong> +33 1 23 45 67 89</p>
            <p><strong>Adresse :</strong> 10 Rue du Bien-être, 75001 Paris, France</p>
        </div>
    </section>
</main>

<?php include '../includes/footer_public.php'; ?>
</body>
</html>
