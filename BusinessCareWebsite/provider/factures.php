<?php
require_once '../includes/db.php';
include 'includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM invoices WHERE company_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['company_id']]);
$invoices = $stmt->fetchAll();
?>

<main class="form-section">
    <h2>Mes Factures</h2>

    <?php if (empty($invoices)): ?>
        <div style="padding: 20px; background-color: #fff3cd; border-left: 5px solid #ffc107; border-radius: 6px;">
            Aucune facture disponible pour le moment.
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.95rem;">
            <thead>
                <tr style="background-color: #003566; color: white;">
                    <th style="padding: 12px;">Date</th>
                    <th style="padding: 12px;">Montant (€)</th>
                    <th style="padding: 12px;">Statut</th>
                    <th style="padding: 12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $facture): ?>
                    <tr style="border-bottom: 1px solid #ccc; background-color: #f9f9f9;">
                        <td style="padding: 12px;"><?= htmlspecialchars(date('d/m/Y', strtotime($facture['created_at']))) ?></td>
                        <td style="padding: 12px;"><?= htmlspecialchars(number_format($facture['amount'], 2)) ?> €</td>
                        <td style="padding: 12px;">
                            <?php if ($facture['status'] === 'paid'): ?>
                                <span style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px;">Payée</span>
                            <?php else: ?>
                                <span style="background-color: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 4px;">Impayée</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px;">
                            <?php if ($facture['status'] !== 'paid'): ?>
                                <a href="#" style="color: #dc3545; font-weight: bold;">Payer</a>
                            <?php else: ?>
                                <a href="download_invoice.php?id=<?= $facture['id'] ?>" target="_blank" style="color: #007bff; font-weight: bold;">Télécharger</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
