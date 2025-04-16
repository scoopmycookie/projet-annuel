<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Business Care</title>
  <link rel="stylesheet" href="../assets/css/public.css">
  <style>
    #tuto-button {
      position: fixed;
      bottom: 20px;
      left: 20px;
      background: #ff9800;
      color: black;
      font-weight: bold;
      padding: 10px 16px;
      border-radius: 30px;
      cursor: pointer;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      z-index: 999;
      font-size: 14px;
    }

    #tutorial-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.85);
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }

    #tutorial-step {
      background: white;
      color: #333;
      padding: 30px 40px;
      border-radius: 12px;
      max-width: 600px;
      width: 90%;
      text-align: center;
      box-shadow: 0 0 30px rgba(0,0,0,0.4);
      font-size: 18px;
    }

    #tutorial-step h2 {
      margin-bottom: 15px;
      font-size: 26px;
    }

    #tutorial-step button {
      margin-top: 20px;
      padding: 10px 24px;
      background: #ff9800;
      color: black;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
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

<!-- ❓ Bouton Tuto -->
<div id="tuto-button" onclick="startTutorial()">❓ Tuto</div>

<!-- 🎓 Tutoriel interactif -->
<div id="tutorial-overlay">
  <div id="tutorial-step">
    <h2 id="tuto-title">👋 Bienvenue sur Business Care</h2>
    <p id="tuto-text">Cliquez sur “Suivant” pour découvrir les fonctionnalités principales.</p>
    <button onclick="nextStep()">Suivant</button>
  </div>
</div>

<script>
  const steps = [
    {
      title: "👋 Bienvenue sur Business Care",
      text: "Cliquez sur “Suivant” pour découvrir les fonctionnalités principales."
    },
    {
      title: "🛠 Services",
      text: "Accédez à tous nos services : bien-être, team building, coaching..."
    },
    {
      title: "📰 Blog & Astuces",
      text: "Explorez nos articles pour améliorer le quotidien de votre équipe."
    },
    {
      title: "📞 Contact",
      text: "Utilisez le formulaire pour demander un devis ou nous poser vos questions."
    },
    {
      title: "✅ Prêt à explorer ?",
      text: "Utilisez le menu en haut pour naviguer librement. Bonne visite !"
    }
  ];

  let currentStep = 0;

  function startTutorial() {
    currentStep = 0;
    document.getElementById("tutorial-overlay").style.display = "flex";
    updateTutorial();
  }

  function nextStep() {
    currentStep++;
    if (currentStep >= steps.length) {
      document.getElementById("tutorial-overlay").style.display = "none";
    } else {
      updateTutorial();
    }
  }

  function updateTutorial() {
    document.getElementById("tuto-title").textContent = steps[currentStep].title;
    document.getElementById("tuto-text").textContent = steps[currentStep].text;
    document.querySelector("#tutorial-step button").textContent = (currentStep === steps.length - 1) ? "Terminer" : "Suivant";
  }
</script>

</body>
</html>
