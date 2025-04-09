<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

// Récupérer la société du client
$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
$company = $data['company'];

// Récupérer les employés
$employees = $conn->prepare("SELECT first_name, last_name, email, phone FROM users WHERE company = ? AND role = 'employee'");
$employees->bind_param("s", $company);
$employees->execute();
$emp_result = $employees->get_result();

// Récupérer les devis
$quotes = $conn->prepare("SELECT * FROM quotes WHERE company = ? ORDER BY created_at DESC LIMIT 5");
$quotes->bind_param("s", $company);
$quotes->execute();
$quotes_result = $quotes->get_result();

// Récupérer les services (si liés à la société)
$services = $conn->prepare("SELECT * FROM services WHERE company = ? ORDER BY created_at DESC LIMIT 5");
$services->bind_param("s", $company);
$services->execute();
$services_result = $services->get_result();

// Factures (prévu pour extension future)
$invoices = []; // Remplace avec ta logique plus tard
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Client</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
</head>
<body>
<?php include '../includes/header_clients.php'; ?>

<main class="container">
    <h1>👋 Bienvenue, <?= htmlspecialchars($first_name . ' ' . $last_name) ?></h1>
    <p>Entreprise : <strong><?= htmlspecialchars($company) ?></strong></p>


    <section>
        <h2>👥 Vos collaborateurs</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($emp = $emp_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                            <td><?= htmlspecialchars($emp['email']) ?></td>
                            <td><?= htmlspecialchars($emp['phone']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h2>📄 Derniers devis</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Formule</th>
                        <th>Prix/employé</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($q = $quotes_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= ucfirst($q['plan']) ?></td>
                            <td><?= number_format($q['price_per_employee'], 2) ?> €</td>
                            <td><?= date('d/m/Y', strtotime($q['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h2>🛠 Services souscrits</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Statut</th>
                        <th>Début</th>
                        <th>Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($srv = $services_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($srv['title']) ?></td>
                            <td><?= htmlspecialchars($srv['description']) ?></td>
                            <td><?= ucfirst($srv['status']) ?></td>
                            <td><?= $srv['start_date'] ?></td>
                            <td><?= $srv['end_date'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

</main>

<?php include '../includes/footer_clients.php'; ?>
</body>
</html>
