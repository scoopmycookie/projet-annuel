<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Devis - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Demandez un Devis</h1>
                <p>Remplissez le formulaire ci-dessous pour obtenir une estimation personnalisée de nos services.</p>
            </div>
        </section>

        <section class="devis-form">
            <div class="container">
                <form action="submit_devis.php" method="POST">
                    <label for="nom">Nom et Prénom</label>
                    <input type="text" id="nom" name="nom" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" required>

                    <label for="service">Service souhaité</label>
                    <select id="service" name="service" required>
                        <option value="">Sélectionnez un service</option>
                        <option value="coaching">Coaching & Bien-être</option>
                        <option value="team_building">Activités de Team Building</option>
                        <option value="formations">Formations & Webinars</option>
                    </select>

                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit" class="btn">Envoyer la demande</button>
                </form>
            </div>
        </section>
    </main>

    <?php include '../includes/footer_public.php'; ?>
</body>
</html>
