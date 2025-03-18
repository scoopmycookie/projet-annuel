<?php include('includes/header.php'); ?>

<div class="hero">
    <h2>Créer un compte</h2>
</div>

<div class="container">
    <h2>📝 Inscription</h2>
    <p>Remplissez le formulaire ci-dessous pour créer votre compte.</p>

    <form action="auth/process_register.php" method="post">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Choisissez votre rôle :</label>
        <select id="role" name="role" required>
            <option value="employe">Employé</option>
            <option value="prestataire">Prestataire</option>
            <option value="admin">Administrateur</option>
        </select>

        <button type="submit" class="btn">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
</div>

<?php include('includes/footer.php'); ?>
