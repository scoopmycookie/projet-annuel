<?php include('includes/header.php'); ?>

<div class="hero">
    <h2>Connexion à votre espace</h2>
</div>

<div class="container">
    <h2>🔐 Connectez-vous</h2>
    <p>Accédez à votre espace personnel en entrant vos identifiants.</p>

    <form action="auth/process_login.php" method="post">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn">Se connecter</button>
    </form>

    <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
</div>

<?php include('includes/footer.php'); ?>
