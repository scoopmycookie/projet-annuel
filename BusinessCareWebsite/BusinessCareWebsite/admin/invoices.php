<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['company_id'], $_POST['employees'])) {
        $update = $pdo->prepare("UPDATE companies SET employees = ? WHERE id = ?");
        $update->execute([intval($_POST['employees']), intval($_POST['company_id'])]);
    }

    if (isset($_POST['update_pricing'])) {
        foreach ($_POST['tarif'] as $id => $valeur) {
            $stmt = $pdo->prepare("UPDATE pricing SET tarif = ? WHERE id = ?");
            $stmt->execute([intval($valeur), intval($id)]);
        }
    }
}

$pricingList = $pdo->query("SELECT * FROM pricing ORDER BY employee_min")->fetchAll(PDO::FETCH_ASSOC);

function getPackFromEmployees($count, $pricingList) {
    foreach ($pricingList as $p) {
        if (is_null($p['employee_max']) && $count >= $p['employee_min']) {
            return ['pack' => $p['pack'], 'tarif' => $p['tarif']];
        }
        if ($count >= $p['employee_min'] && $count <= $p['employee_max']) {
            return ['pack' => $p['pack'], 'tarif' => $p['tarif']];
        }
    }
    return ['pack' => 'Inconnu', 'tarif' => 0];
}

$stmt = $pdo->query("
    SELECT invoices.*, companies.name AS company_name, companies.email AS company_email, companies.employees, companies.id AS company_id
    FROM invoices
    JOIN companies ON invoices.company_id = companies.id
    ORDER BY invoices.created_at DESC
");

$invoices = $stmt->fetchAll();
$today = date('Y-m-d');
?>

<?php include 'includes/header.php'; ?>

<section class="form-section">
    <h2>Liste des factures avec grille tarifaire</h2>

    <?php if (empty($invoices)): ?>
        <p>Aucune facture enregistrée.</p>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f2f2f2;">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Entreprise</th>
                    <th style="padding: 10px;">Email</th>
                    <th style="padding: 10px;">Effectif</th>
                    <th style="padding: 10px;">Pack</th>
                    <th style="padding: 10px;">Tarif/Salarié (€)</th>
                    <th style="padding: 10px;">Montant total (€)</th>
                    <th style="padding: 10px;">Fin de contrat</th>
                    <th style="padding: 10px;">PDF</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <?php
                        $info = getPackFromEmployees($invoice['employees'], $pricingList);
                        $total = $info['tarif'] * $invoice['employees'];

                        if (!empty($invoice['end_date']) && $invoice['end_date'] <= $today && !$invoice['sent']) {
                            include_once 'send_invoice_auto.php';
                            sendContractInvoice($invoice['id'], $pdo);
                        }
                    ?>
                    <tr>
                        <td style="padding: 8px;"><?= $invoice['id'] ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($invoice['company_name']) ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($invoice['company_email']) ?></td>
                        <td style="padding: 8px;">
                            <form method="POST" style="display:inline-flex; gap: 5px;">
                                <input type="hidden" name="company_id" value="<?= $invoice['company_id'] ?>">
                                <input type="number" name="employees" value="<?= $invoice['employees'] ?>" min="1" required style="width: 60px;">
                                <button type="submit">OK</button>
                            </form>
                        </td>
                        <td style="padding: 8px;"><?= $info['pack'] ?></td>
                        <td style="padding: 8px;"><?= $info['tarif'] ?></td>
                        <td style="padding: 8px;"><?= number_format($total, 2, ',', ' ') ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($invoice['end_date']) ?></td>
                        <td style="padding: 8px;">
                            <a href="generate_pdf.php?id=<?= $invoice['id'] ?>" target="_blank">PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h3 style="margin-top: 40px;">Modifier la grille tarifaire</h3>
    <form method="POST">
        <input type="hidden" name="update_pricing" value="1">
        <table style="width:100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #ddd;">
                    <th style="padding: 10px;">Pack</th>
                    <th style="padding: 10px;">Effectif</th>
                    <th style="padding: 10px;">Tarif annuel / salarié (€)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pricingList as $p): ?>
                    <tr>
                        <td style="padding: 8px;"><?= $p['pack'] ?></td>
                        <td style="padding: 8px;">
                            <?php
                                echo is_null($p['employee_max'])
                                    ? "à partir de {$p['employee_min']}"
                                    : "{$p['employee_min']} à {$p['employee_max']}";
                            ?>
                        </td>
                        <td style="padding: 8px;">
                            <input type="number" name="tarif[<?= $p['id'] ?>]" value="<?= $p['tarif'] ?>" min="0" required>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit">Mettre à jour la grille</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
