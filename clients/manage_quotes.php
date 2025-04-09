<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

// Récupérer le nom de l'entreprise
$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc()['company'];

// Récupérer les devis de cette entreprise
$quotes_stmt = $conn->prepare("SELECT * FROM quotes WHERE company = ? ORDER BY created_at DESC");
$quotes_stmt->bind_param("s", $company);
$quotes_stmt->execute();
$quotes = $quotes_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes devis - Business Care</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
</head>
<body>

<?php include '../includes/header_clients.php'; ?>

<main class="container">
    <h1>📄 Mes devis</h1>
    <p>Entreprise : <strong><?= htmlspecialchars($company) ?></strong></p>

    <?php if ($quotes->num_rows === 0): ?>
        <p>Aucun devis pour le moment.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Formule</th>
                        <th>Tarif / Employé</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($quote = $quotes->fetch_assoc()): ?>
                        <tr>
                            <td><?= ucfirst($quote['plan']) ?></td>
                            <td><?= number_format($quote['price_per_employee'], 2) ?> €</td>
                            <td><?= date('d/m/Y', strtotime($quote['created_at'])) ?></td>
                            <td>
                                <a href="generate_invoice.php?quote_id=<?= $quote['id'] ?>" class="btn btn-green">💳 Générer la facture</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer_clients.php'; ?>
</body>
</html>
