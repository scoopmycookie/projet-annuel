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

// Statistiques globales
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$total_companies = $conn->query("SELECT COUNT(*) as total FROM companies")->fetch_assoc()['total'];
$total_quotes = $conn->query("SELECT COUNT(*) as total FROM quotes")->fetch_assoc()['total'];
$total_services = $conn->query("SELECT COUNT(*) as total FROM services")->fetch_assoc()['total'];

// Activités récentes
$recent_users = $conn->query("SELECT first_name, last_name, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$recent_quotes = $conn->query("SELECT id, company, created_at FROM quotes ORDER BY created_at DESC LIMIT 5");

// Entreprises et fournisseurs en attente de validation
$pending_companies = $conn->query("SELECT id, name, email, created_at FROM companies WHERE is_verified = 0 ORDER BY created_at DESC");
$pending_suppliers = $conn->query("SELECT id, name, email, created_at FROM providers WHERE is_verified = 0 ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Business Care</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .stats-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin: 30px 0;
        }
        .card {
            background: #1f1f1f;
            border-left: 6px solid #ff9800;
            padding: 20px;
            flex: 1;
            border-radius: 8px;
            color: white;
            min-width: 200px;
            text-align: center;
        }
        .card h3 {
            margin-bottom: 10px;
        }

        .activity-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .activity-box {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            flex: 1;
            min-width: 300px;
        }

        .activity-box ul {
            list-style: none;
            padding-left: 0;
        }

        .activity-box li {
            margin-bottom: 8px;
            border-bottom: 1px solid #333;
            padding-bottom: 6px;
        }

        .pending-section {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .pending-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .pending-table th, .pending-table td {
            padding: 12px;
            border-bottom: 1px solid #444;
            text-align: left;
            color: white;
        }

        .pending-table th {
            background: #ff9800;
        }

        .pending-table tr:hover {
            background: #444;
        }

        .calendar iframe {
            border: none;
            width: 100%;
            height: 400px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>👋 Bienvenue, <?= htmlspecialchars($first_name . ' ' . $last_name) ?> !</h1>
    <p>Voici un aperçu global de votre espace d'administration.</p>

    <!-- Statistiques -->
    <section class="stats-cards">
        <div class="card">
            <h3>👥 Utilisateurs</h3>
            <p><?= $total_users ?></p>
        </div>
        <div class="card">
            <h3>🏢 Entreprises</h3>
            <p><?= $total_companies ?></p>
        </div>
        <div class="card">
            <h3>📄 Devis</h3>
            <p><?= $total_quotes ?></p>
        </div>
        <div class="card">
            <h3>🛠 Services</h3>
            <p><?= $total_services ?></p>
        </div>
    </section>

    <!-- Activités récentes -->
    <section class="recent-activities">
        <h2>📌 Activités récentes</h2>
        <div class="activity-container">
            <div class="activity-box">
                <h3>🆕 Nouveaux utilisateurs</h3>
                <ul>
                    <?php while ($user = $recent_users->fetch_assoc()): ?>
                        <li>
                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                            (<?= ucfirst($user['role']) ?>)
                            - <?= date("d/m/Y", strtotime($user['created_at'])) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="activity-box">
                <h3>📄 Nouveaux devis</h3>
                <ul>
                    <?php while ($quote = $recent_quotes->fetch_assoc()): ?>
                        <li>
                            Devis #<?= $quote['id'] ?> — Entreprise ID: <?= $quote['company'] ?> — 
                            <?= date("d/m/Y", strtotime($quote['created_at'])) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </section>

    <!-- Entreprises en attente de validation -->
    <section class="pending-section">
        <h2>🏢 Entreprises en attente de validation</h2>
        <table class="pending-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Créée le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($company = $pending_companies->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($company['name']) ?></td>
                        <td><?= htmlspecialchars($company['email']) ?></td>
                        <td><?= date("d/m/Y", strtotime($company['created_at'])) ?></td>
                        <td>
                            <a href="validate_company.php?id=<?= $company['id'] ?>" class="btn btn-green">Valider</a>
                            <a href="delete_company.php?id=<?= $company['id'] ?>" class="btn btn-red">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- Fournisseurs en attente de validation -->
    <section class="pending-section">
        <h2>🧑‍💼 Fournisseurs en attente de validation</h2>
        <table class="pending-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Créée le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($supplier = $pending_suppliers->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($supplier['name']) ?></td>
                        <td><?= htmlspecialchars($supplier['email']) ?></td>
                        <td><?= date("d/m/Y", strtotime($supplier['created_at'])) ?></td>
                        <td>
                            <a href="validate_supplier.php?id=<?= $supplier['id'] ?>" class="btn btn-green">Valider</a>
                            <a href="delete_supplier.php?id=<?= $supplier['id'] ?>" class="btn btn-red">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- Planning -->
    <section class="planning">
        <h2>📅 Planning des événements</h2>
        <div class="calendar">
            <iframe src="https://calendar.google.com/calendar/embed?src=votre_calendrier_google"
                style="border: 0" width="100%" height="400"></iframe>
        </div>
    </section>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
