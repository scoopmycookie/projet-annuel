<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>
    
    <main>
        <section class="hero">
            <div class="container">
                <h1>Bienvenue sur Business Care</h1>
                <p>Votre partenaire pour le bien-être et la cohésion en entreprise.</p>
                <a href="services.php" class="btn">Découvrir nos services</a>
            </div>
        </section>
        
        <section class="about">
            <div class="container">
                <h2>Qui sommes-nous ?</h2>
                <p>Business Care améliore la santé, le bien-être et la cohésion des équipes en entreprise à travers une multitude de services et événements.</p>
            </div>
        </section>
        
        <section class="services-highlight">
            <div class="container">
                <h2>Nos Services Populaires</h2>
                <div class="service-boxes">
                    <div class="service-box">
                        <img src="../assets/images/service1.jpg" alt="Service 1">
                        <h3>Coaching & Bien-être</h3>
                        <p>Des séances de coaching pour améliorer votre équilibre mental et émotionnel.</p>
                        <a href="services.php" class="btn">En savoir plus</a>
                    </div>
                    <div class="service-box">
                        <img src="../assets/images/service2.jpg" alt="Service 2">
                        <h3>Activités de Team Building</h3>
                        <p>Renforcez la cohésion de votre équipe grâce à nos événements interactifs.</p>
                        <a href="services.php" class="btn">Découvrir</a>
                    </div>
                    <div class="service-box">
                        <img src="../assets/images/service3.jpg" alt="Service 3">
                        <h3>Formations & Webinars</h3>
                        <p>Des formations en ligne pour développer vos compétences et votre bien-être.</p>
                        <a href="services.php" class="btn">Voir plus</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog">
            <div class="container">
                <h2>Derniers Articles</h2>
                <div class="blog-posts">
                    <div class="blog-post">
                        <img src="../assets/images/blog1.jpg" alt="Blog 1">
                        <h3>Comment améliorer le bien-être au travail</h3>
                        <p>Découvrez des stratégies efficaces pour rendre votre environnement professionnel plus agréable.</p>
                        <a href="blog.php" class="btn">Lire plus</a>
                    </div>
                    <div class="blog-post">
                        <img src="../assets/images/blog2.jpg" alt="Blog 2">
                        <h3>Les avantages du team building</h3>
                        <p>Pourquoi organiser des activités de groupe peut améliorer la performance de votre entreprise.</p>
                        <a href="blog.php" class="btn">Lire plus</a>
                    </div>
                    <div class="blog-post">
                        <h3>Gestion du stress en entreprise</h3>
                        <p>Apprenez des techniques simples pour mieux gérer la pression au travail.</p>
                        <a href="blog.php" class="btn">Lire plus</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-banner">
            <div class="container">
                <h2>Contactez-nous pour un devis personnalisé</h2>
                <p>Nous sommes là pour vous accompagner dans votre transformation.</p>
                <a href="contact.php" class="btn">Nous Contacter</a>
            </div>
        </section>

        <section class="faq">
            <div class="container">
                <h2>Questions Fréquentes</h2>
                <div class="faq-item">
                    <h3>Quels types de services proposez-vous ?</h3>
                    <p>Nous proposons des formations, du coaching, des événements de team building et des activités bien-être.</p>
                </div>
                <div class="faq-item">
                    <h3>Comment réserver une prestation ?</h3>
                    <p>Vous pouvez nous contacter directement via notre formulaire de contact ou demander un devis personnalisé.</p>
                </div>
                <div class="faq-item">
                    <h3>Quels sont les tarifs ?</h3>
                    <p>Nos tarifs varient selon le service choisi et le nombre de participants. Contactez-nous pour un devis détaillé.</p>
                </div>
            </div>
        </section>
    </main>
    
    <?php include '../includes/footer_public.php'; ?>
</body>
</html>
