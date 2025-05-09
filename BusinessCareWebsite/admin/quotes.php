<?php
require_once '../includes/db.php';
include 'includes/header.php';

$message = null;

if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['approve', 'reject'])) {
        $newStatus = $action === 'approve' ? 'approved' : 'rejected';
        $pdo->prepare("UPDATE quotes SET status = ? WHERE id = ?")->execute([$newStatus, $id]);
        $message = "Statut du devis mis à jour.";
    }

    if ($action === 'invoice') {
        $stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = ? AND status = 'approved'");
        $stmt->execute([$id]);
        $quote = $stmt->fetch();

        if ($quote) {
            $pdo->prepare("
                INSERT INTO invoices (company_id, quote_id, amount, status, due_date)
                VALUES (?, ?, ?, 'unpaid', DATE_ADD(NOW(), INTERVAL 30 DAY))
            ")->execute([$quote['company_id'], $quote['id'], $quote['amount']]);
            $message = "Facture créée à partir du devis.";
        }
    }
}

$stmt = $pdo->query("
    SELECT quotes.*, companies.name AS company_name
    FROM quotes
    JOIN companies ON quotes.company_id = companies.id
    ORDER BY quotes.created_at DESC
");

$quotes = $stmt->fetchAll();
?>

<section class="form-section">
    <h2>Liste des devis</h2>

    <?php if ($message): ?>
        <p style="color: green;"><?= $message ?></p>
    <?php endif; ?>

    <?php if (empty($quotes)): ?>
        <p>Aucun devis disponible.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f2f2f2;">
                    <th style="padding: 10px; border: 1px solid #ccc;">ID</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Entreprise</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Montant (€)</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Statut</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Date</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotes as $quote): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= $quote['id'] ?></td>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= htmlspecialchars($quote['company_name']) ?></td>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= number_format($quote['amount'], 2, ',', ' ') ?> €</td>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= ucfirst($quote['status']) ?></td>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= htmlspecialchars($quote['created_at']) ?></td>
                        <td style="padding: 8px; border: 1px solid #ccc;">
                            <?php if ($quote['status'] === 'pending'): ?>
                                <a href="?action=approve&id=<?= $quote['id'] ?>" onclick="return confirm('Valider ce devis ?')">Valider</a> |
                                <a href="?action=reject&id=<?= $quote['id'] ?>" onclick="return confirm('Refuser ce devis ?')">Refuser</a>
                            <?php elseif ($quote['status'] === 'approved'): ?>
                                <a href="?action=invoice&id=<?= $quote['id'] ?>" onclick="return confirm('Créer une facture ?')">Créer facture</a>
                            <?php else: ?>
                                Aucun
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
