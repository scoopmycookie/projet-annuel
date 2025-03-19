<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Services - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Nos Services</h1>
                <p>Découvrez nos solutions pour améliorer le bien-être et la cohésion au sein de votre entreprise.</p>
            </div>
        </section>

        <section class="services-list">
            <div class="container">
                <div class="service-box">
                    <img src="../assets/images/service1.jpg" alt="Coaching & Bien-être">
                    <h2>Coaching & Bien-être</h2>
                    <p>Des séances de coaching pour améliorer votre équilibre mental et émotionnel.</p>
                    <a href="#" class="btn">En savoir plus</a>
                </div>
                
                <div class="service-box">
                    <img src="../assets/images/service2.jpg" alt="Activités de Team Building">
                    <h2>Activités de Team Building</h2>
                    <p>Renforcez la cohésion de votre équipe grâce à nos événements interactifs.</p>
                    <a href="#" class="btn">Découvrir</a>
                </div>
                
                <div class="service-box">
                    <img src="../assets/images/service3.jpg" alt="Formations & Webinars">
                    <h2>Formations & Webinars</h2>
                    <p>Des formations en ligne pour développer vos compétences et votre bien-être.</p>
                    <a href="#" class="btn">Voir plus</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer_public.php'; ?>
</body>
</html>
