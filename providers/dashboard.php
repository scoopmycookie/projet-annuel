<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

// Récupération du nom de l'entreprise du fournisseur
$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$company = $data['company'];

// Récupération des services liés à cette entreprise
$services = $conn->prepare("SELECT * FROM services WHERE company = ?");
$services->bind_param("s", $company);
$services->execute();
$services_result = $services->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Fournisseur</title>
    <link rel="stylesheet" href="../assets/css/providers.css">
</head>
<body>

<?php include '../includes/header_providers.php'; ?>

<main class="container">
    <h1>👋 Bonjour, <?= htmlspecialchars($first_name . ' ' . $last_name) ?> !</h1>
    <p>Entreprise fournisseur : <strong><?= htmlspecialchars($company) ?></strong></p>

    <section>
        <h2>📦 Services que vous proposez</h2>
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
                    <?php if ($services_result->num_rows > 0): ?>
                        <?php while ($srv = $services_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($srv['title']) ?></td>
                                <td><?= htmlspecialchars($srv['description']) ?></td>
                                <td><?= ucfirst($srv['status']) ?></td>
                                <td><?= $srv['start_date'] ?></td>
                                <td><?= $srv['end_date'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Aucun service trouvé pour votre entreprise.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h2>🛠 Accès rapides</h2>
        <div class="dashboard-options">
            <a href="manage_services.php" class="btn">Gérer mes services</a>
            <a href="calendar.php" class="btn">Voir mon planning</a>
            <a href="messages.php" class="btn">Messages reçus</a>
        </div>
    </section>
</main>

<?php include '../includes/footer_providers.php'; ?>
</body>
</html>
