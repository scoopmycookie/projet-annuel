<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Care</title>
    <link rel="stylesheet" href="/business-care/assets/css/public.css">
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <a href="/business-care/public/index.php">
                <img src="/assets/images/logo.png" alt="Business Care Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="/public/index.php">Accueil</a></li>
                <li><a href="/public/services.php">Services</a></li>
                <li><a href="/public/devis.php">Devis</a></li>
                <li><a href="/public/contact.php">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="/employees/dashboard.php">Espace Personnel</a></li>
                    <li><a href="/public/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/public/login.php">Connexion</a></li>
                    <li><a href="/public/register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>