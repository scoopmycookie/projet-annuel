<?php include('includes/header.php'); ?>

<div class="hero">
    <h2>Contactez-nous</h2>
</div>

<div class="container">
    <h2>📩 Nous contacter</h2>
    <p>Remplissez ce formulaire et nous vous répondrons dans les plus brefs délais.</p>

    <form action="send_contact.php" method="post">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit" class="btn">Envoyer</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
