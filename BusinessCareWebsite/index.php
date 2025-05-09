<?php include 'includes/header.php'; ?>


<section class="hero">
    <div class="hero-content">
        <h2>Améliorez le bien-être de vos équipes</h2>
        <p>Business Care accompagne les entreprises dans leur transformation humaine grâce à des services de santé, de cohésion et de bien-être en entreprise.</p>
        <a href="#devis" class="cta-button">Demander un devis</a>
    </div>
</section>

<section id="devis" class="devis-section">
    <h3>Demande de devis personnalisée</h3>
    <form action="submit_quote.php" method="POST" class="quote-form">
        <input type="text" name="company" placeholder="Nom de l'entreprise" required>
        <input type="email" name="email" placeholder="Email de contact" required>
        <textarea name="details" placeholder="Décrivez vos besoins (services, effectif, etc.)" required></textarea>
        <button type="submit">Envoyer la demande</button>
    </form>
</section>

<section class="services-preview">
    <h3>Nos services populaires</h3>
    <ul class="service-list">
        <li>
            <h4>Webinars santé mentale</h4>
            <p>Sensibilisation et accompagnement au bien-être psychologique des salariés.</p>
        </li>
        <li>
            <h4>Séances de yoga & sport</h4>
            <p>Activités physiques sur site ou à distance pour améliorer la cohésion d'équipe.</p>
        </li>
        <li>
            <h4>Rendez-vous thérapeutiques</h4>
            <p>Prise de rendez-vous confidentiels avec des professionnels qualifiés.</p>
        </li>
    </ul>
</section>

<?php include 'includes/footer.php'; ?>
