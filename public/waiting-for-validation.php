<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: /public/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte en attente de validation</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

<main>
    <section class="hero">
        <div class="container">
            <h1>Compte en attente de validation</h1>
            <p>Merci pour votre inscription ! Votre compte est actuellement en attente de validation par un administrateur.</p>
            <p>Vous recevrez un e-mail une fois votre compte validé, et vous pourrez accéder à votre espace personnel.</p>
            <p>Si vous avez des questions, n'hésitez pas à <a href="/public/contact.php">nous contacter</a>.</p>
        </div>
    </section>
</main>

<?php include '../includes/footer_public.php'; ?>
</body>
</html>
