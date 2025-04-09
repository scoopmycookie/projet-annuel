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

// Récupérer les factures du client
$sql = "
    SELECT invoices.*, quotes.plan, quotes.price_per_employee 
    FROM invoices
    JOIN quotes ON invoices.quote_id = quotes.id
    WHERE invoices.user_id = ?
    ORDER BY invoices.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$invoices = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes factures - Business Care</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
</head>
<body>
<?php include '../includes/header_clients.php'; ?>

<main class="container">
    <h1>💳 Mes factures</h1>

    <?php if ($invoices->num_rows === 0): ?>
        <p>Aucune facture générée pour l’instant.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Facture #</th>
                        <th>Formule</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($invoice = $invoices->fetch_assoc()) : ?>
                        <tr>
                            <td>#<?= $invoice['id'] ?></td>
                            <td><?= ucfirst($invoice['plan']) ?></td>
                            <td><?= number_format($invoice['amount'], 2) ?> €</td>
                            <td><?= date('d/m/Y', strtotime($invoice['created_at'])) ?></td>
                            <td><a href="view_invoice.php?id=<?= $invoice['id'] ?>" class="btn">📥 Voir PDF</a></td>
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
