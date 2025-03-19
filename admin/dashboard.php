<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_admin.php'; ?>

    <main>
        <section class="admin-dashboard">
            <div class="container">
                <h1>Bienvenue, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>!</h1>
                <p>Gestion de votre espace administrateur</p>

                <div class="dashboard-options">
                    <a href="manage_users.php" class="btn">Gérer les utilisateurs</a>
                    <a href="manage_companies.php" class="btn">Gérer les entreprises</a>
                    <a href="manage_services.php" class="btn">Gérer les services</a>
                    <a href="manage_quotes.php" class="btn">Gérer les devis</a>
                    <a href="manage_messages.php" class="btn">Gérer les messages</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer_admin.php'; ?>
</body>
</html>