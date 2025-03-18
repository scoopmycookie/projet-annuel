<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>

<?php include('..includes/header.php'); ?>

<div class="container">
    <h2>Bienvenue, <?php echo $_SESSION["user"]; ?> !</h2>
    <p>Vous êtes connecté(e) à votre espace personnel.</p>
    <a href="auth/logout.php" class="btn">Se déconnecter</a>
</div>

<?php include('includes/footer.php'); ?>
