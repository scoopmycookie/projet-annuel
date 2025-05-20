<?php
require_once '../includes/db.php';
include 'includes/header.php';

session_start();
$provider_id = $_SESSION['user_id'];

// Récupérer les factures du prestataire
$stmt = $pdo->prepare("SELECT * FROM provider_invoices WHERE provider_id = ? ORDER BY year DESC");
$stmt->execute([$provider_id]);
$invoices = $stmt->fetchAll();

// Récupérer les services par année
$services_stmt = $pdo->prepare("SELECT * FROM services WHERE provider_id = ? ORDER BY service_date DESC");
$services_stmt->execute([$provider_id]);
$services = $services_stmt->fetchAll();
?>

<main class="form-section">
    <h2>Mes Factures Prestataire</h2>

    <?php if (empty($invoices)): ?>
        <div style="padding: 20px; background-color: #fff3cd; border-left: 5px solid #ffc107; border-radius: 6px;">
            Aucune facture disponible pour le moment.
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.95rem;">
            <thead>
                <tr style="background-color: #003566; color: white;">
                    <th style="padding: 12px;">Année</th>
                    <th style="padding: 12px;">Montant Total (€)</th>
                    <th style="padding: 12px;">Statut</th>
                    <th style="padding: 12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $facture): ?>
                    <tr style="border-bottom: 1px solid #ccc; background-color: #f9f9f9;">
                        <td style="padding: 12px;">
                            <?= htmlspecialchars($facture['year']) ?>
                        </td>
                        <td style="padding: 12px;">
                            <?= htmlspecialchars(number_format($facture['amount'], 2)) ?> €
                        </td>
                        <td style="padding: 12px;">
                            <?php if ($facture['status'] === 'paid'): ?>
                                <span style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px;">Payée</span>
                            <?php else: ?>
                                <span style="background-color: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 4px;">Impayée</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px;">
                            <a href="download_provider_invoice.php?id=<?= $facture['id'] ?>" target="_blank" style="color: #007bff; font-weight: bold;">Télécharger</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h3 style="margin-top: 40px;">Détail des prestations par année</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.95rem;">
        <thead>
            <tr style="background-color:rgb(2, 59, 120); color: white;">
                <th style="padding: 10px;">Date</th>
                <th style="padding: 10px;">Titre</th>
                <th style="padding: 10px;">Prix (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $srv): ?>
                <tr style="border-bottom: 1px solid #ccc;">
                    <td style="padding: 10px;"><?= htmlspecialchars(date('d/m/Y', strtotime($srv['service_date']))) ?></td>
                    <td style="padding: 10px;"><?= htmlspecialchars($srv['title']) ?></td>
                    <td style="padding: 10px;"><?= number_format($srv['price'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'includes/footer.php'; ?>