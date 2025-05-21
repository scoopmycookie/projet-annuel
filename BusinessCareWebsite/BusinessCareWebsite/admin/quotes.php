<?php
require_once '../includes/db.php';
include 'includes/header.php';

$message = null;

// === Actions sur les devis internes ===
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

// === Actions sur les devis publics ===
if (isset($_GET['public_action'], $_GET['public_id'])) {
    $id = (int) $_GET['public_id'];
    $action = $_GET['public_action'];

    if (in_array($action, ['approve', 'reject'])) {
        $newStatus = $action === 'approve' ? 'approved' : 'rejected';
        $pdo->prepare("UPDATE public_quotes SET status = ? WHERE id = ?")->execute([$newStatus, $id]);
        $message = "Statut du devis public mis à jour.";
    }
}

// === Récupération des devis ===
$quotes = $pdo->query("
    SELECT quotes.*, companies.name AS company_name
    FROM quotes
    JOIN companies ON quotes.company_id = companies.id
    ORDER BY quotes.created_at DESC
")->fetchAll();

$publicQuotes = $pdo->query("
    SELECT * FROM public_quotes
    ORDER BY created_at DESC
")->fetchAll();
?>

<section class="form-section">
    <h2>Devis internes</h2>

    <?php if ($message): ?>
        <p style="color: green;"><?= $message ?></p>
    <?php endif; ?>

    <?php if (empty($quotes)): ?>
        <p>Aucun devis interne trouvé.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f2f2f2;">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Entreprise</th>
                    <th style="padding: 10px;">Montant (€)</th>
                    <th style="padding: 10px;">Statut</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotes as $q): ?>
                    <tr>
                        <td style="padding: 8px;"><?= $q['id'] ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($q['company_name']) ?></td>
                        <td style="padding: 8px;"><?= number_format($q['amount'], 2, ',', ' ') ?> €</td>
                        <td style="padding: 8px;"><?= ucfirst($q['status']) ?></td>
                        <td style="padding: 8px;"><?= $q['created_at'] ?></td>
                        <td style="padding: 8px;">
                            <?php if ($q['status'] === 'pending'): ?>
                                <a href="?action=approve&id=<?= $q['id'] ?>" onclick="return confirm('Valider ce devis ?')">Valider</a> |
                                <a href="?action=reject&id=<?= $q['id'] ?>" onclick="return confirm('Refuser ce devis ?')">Refuser</a>
                            <?php elseif ($q['status'] === 'approved'): ?>
                                <a href="?action=invoice&id=<?= $q['id'] ?>" onclick="return confirm('Créer une facture ?')">Créer facture</a>
                            <?php else: ?>
                                <span style="color: gray;">Aucune action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2 style="margin-top: 60px;">Devis publics (sans compte)</h2>

    <?php if (empty($publicQuotes)): ?>
        <p>Aucun devis public reçu.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9f9f9;">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Nom de l'entreprise</th>
                    <th style="padding: 10px;">Email</th>
                    <th style="padding: 10px;">Montant (€)</th>
                    <th style="padding: 10px;">Statut</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($publicQuotes as $pq): ?>
                    <tr>
                        <td style="padding: 8px;"><?= $pq['id'] ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($pq['company_name']) ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($pq['email']) ?></td>
                        <td style="padding: 8px;"><?= number_format($pq['amount'], 2, ',', ' ') ?> €</td>
                        <td style="padding: 8px;"><?= ucfirst($pq['status']) ?></td>
                        <td style="padding: 8px;"><?= $pq['created_at'] ?></td>
                        <td style="padding: 8px;">
                            <?php if ($pq['status'] === 'pending'): ?>
                                <a href="?public_action=approve&public_id=<?= $pq['id'] ?>" onclick="return confirm('Valider ce devis ?')">Valider</a> |
                                <a href="?public_action=reject&public_id=<?= $pq['id'] ?>" onclick="return confirm('Refuser ce devis ?')">Refuser</a>
                            <?php else: ?>
                                <span style="color: <?= $pq['status'] === 'approved' ? 'green' : 'red' ?>;">
                                    <?= ucfirst($pq['status']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
