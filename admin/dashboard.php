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

// Récupérer les activités récentes (ex: nouveaux utilisateurs, nouveaux devis)
$recent_users_query = "SELECT first_name, last_name, role, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$recent_users_result = $conn->query($recent_users_query);

$recent_quotes_query = "SELECT id, company, created_at FROM quotes ORDER BY created_at DESC LIMIT 5";
$recent_quotes_result = $conn->query($recent_quotes_query);
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
                <main class="dashboard">
        <div class="container">
            <h1>👋 Bonjour, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?> !</h1>
        </div>
    </main>

                
                <!-- Section Activités Récentes -->
                <section class="recent-activities">
                    <h2>📌 Activités récentes</h2>
                    <div class="activity-container">
                        <div class="activity-box">
                            <h3>🆕 Nouveaux utilisateurs</h3>
                            <ul>
                                <?php while ($user = $recent_users_result->fetch_assoc()): ?>
                                    <li><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'] . ' (' . ucfirst($user['role']) . ')'); ?> - <?php echo date("d/m/Y", strtotime($user['created_at'])); ?></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>

                        <div class="activity-box">
                            <h3>📄 Nouveaux devis</h3>
                            <ul>
                                <?php while ($quote = $recent_quotes_result->fetch_assoc()): ?>
                                    <li>Devis #<?php echo htmlspecialchars($quote['id']); ?> - Entreprise: <?php echo htmlspecialchars($quote['company']); ?> - <?php echo date("d/m/Y", strtotime($quote['created_at'])); ?></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Section Planning -->
                <section class="planning">
                    <h2>📅 Planning des événements</h2>
                    <div class="calendar">
                        <iframe src="https://calendar.google.com/calendar/embed?src=votre_calendrier_google" style="border: 0" width="100%" height="400"></iframe>
                    </div>
                </section>

            </div>
        </section>
    </main>

    <?php include '../includes/footer_admin.php'; ?>
</body>
</html>
