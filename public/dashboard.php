<?php
session_start();
$user_logged = isset($_SESSION['user_id']);
$first_name = $user_logged ? $_SESSION['first_name'] : null;
$last_name  = $user_logged ? $_SESSION['last_name']  : null;
$role       = $user_logged ? $_SESSION['role'] : 'visiteur';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
    <style>
        .dashboard-welcome {
            text-align: center;
            background: #f1f1f1;
            padding: 50px 20px;
            border-radius: 10px;
            margin-top: 40px;
        }

        .dashboard-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        .dashboard-actions a {
            background-color: #28a745;
            padding: 15px 30px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }

        .dashboard-actions a:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<?php include '../includes/header_public.php'; ?>

<main class="container">

    <section class="dashboard-welcome">
        <h1>
            👋 Bienvenue
            <?= $user_logged ? htmlspecialchars($first_name . ' ' . $last_name) : 'sur votre tableau de bord' ?> !
        </h1>
        <p>
            <?= $user_logged
                ? "Vous êtes connecté en tant que <strong>$role</strong>."
                : "Connectez-vous ou explorez nos services pour votre entreprise." ?>
        </p>
    </section>

    <section class="dashboard-actions">
        <a href="services.php">🛠 Voir les services</a>
        <?php if ($user_logged): ?>
            <a href="profile.php">👤 Mon profil</a>
            <a href="logout.php">🚪 Se déconnecter</a>
        <?php else: ?>
            <a href="login.php">🔐 Se connecter</a>
            <a href="register.php">📝 Créer un compte</a>
        <?php endif; ?>
        <a href="contact.php">📩 Nous contacter</a>
    </section>

</main>

<?php include '../includes/footer_public.php'; ?>

</body>
</html>
