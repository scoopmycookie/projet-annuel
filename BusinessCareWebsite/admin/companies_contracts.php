<?php
require_once '../includes/db.php';
include 'includes/header.php';

$companyId = isset($_GET['company_id']) ? (int)$_GET['company_id'] : 0;
if (!$companyId) {
    echo "<p>Identifiant de société manquant.</p>";
    include 'includes/footer.php';
    exit;
}

// Récupérer les infos de la société
$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$companyId]);
$company = $stmt->fetch();
if (!$company) {
    echo "<p>Société introuvable.</p>";
    include 'includes/footer.php';
    exit;
}

// Supprimer un contrat
if (isset($_GET['delete'])) {
    $contractId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM contracts WHERE id = ? AND company_id = ?")->execute([$contractId, $companyId]);
    header("Location: companies_contracts.php?company_id=$companyId");
    exit;
}

// Récupérer un contrat pour modification
$editContract = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM contracts WHERE id = ? AND company_id = ?");
    $stmt->execute([(int)$_GET['edit'], $companyId]);
    $editContract = $stmt->fetch();
}

// Ajouter ou modifier un contrat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE contracts SET plan = ?, start_date = ?, end_date = ?, amount = ?, status = ? WHERE id = ? AND company_id = ?");
        $stmt->execute([
            $_POST['plan'], $_POST['start_date'], $_POST['end_date'], $_POST['amount'], $_POST['status'], $_POST['id'], $companyId
        ]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO contracts (company_id, plan, start_date, end_date, amount, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $companyId,
            $_POST['plan'],
            $_POST['start_date'],
            $_POST['end_date'],
            $_POST['amount'],
            $_POST['status']
        ]);
    }
    header("Location: companies_contracts.php?company_id=$companyId");
    exit;
}

// Liste des contrats
$stmt = $pdo->prepare("SELECT * FROM contracts WHERE company_id = ? ORDER BY start_date DESC");
$stmt->execute([$companyId]);
$contracts = $stmt->fetchAll();
?>

<h2>Contrats de <?= htmlspecialchars($company['name']) ?></h2>

<form method="POST" style="max-width:600px; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); margin-bottom:30px;">
    <h3><?= $editContract ? 'Modifier le contrat' : 'Ajouter un contrat' ?></h3>
    <input type="hidden" name="id" value="<?= $editContract['id'] ?? '' ?>">
    <label>Plan :</label>
    <select name="plan" required>
        <option value="starter" <?= (isset($editContract) && $editContract['plan'] === 'starter') ? 'selected' : '' ?>>Starter</option>
        <option value="basic" <?= (isset($editContract) && $editContract['plan'] === 'basic') ? 'selected' : '' ?>>Basic</option>
        <option value="premium" <?= (isset($editContract) && $editContract['plan'] === 'premium') ? 'selected' : '' ?>>Premium</option>
    </select>
    <label>Date de début :</label>
    <input type="date" name="start_date" value="<?= $editContract['start_date'] ?? '' ?>" required>
    <label>Date de fin :</label>
    <input type="date" name="end_date" value="<?= $editContract['end_date'] ?? '' ?>">
    <label>Montant :</label>
    <input type="number" step="0.01" name="amount" value="<?= $editContract['amount'] ?? '' ?>" required>
    <label>Statut :</label>
    <select name="status">
        <option value="pending" <?= (isset($editContract) && $editContract['status'] === 'pending') ? 'selected' : '' ?>>En attente</option>
        <option value="active" <?= (isset($editContract) && $editContract['status'] === 'active') ? 'selected' : '' ?>>Actif</option>
        <option value="expired" <?= (isset($editContract) && $editContract['status'] === 'expired') ? 'selected' : '' ?>>Expiré</option>
    </select>
    <button type="submit" style="margin-top:10px;">Enregistrer</button>
</form>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; background:#fff; border-radius:8px; overflow:hidden;">
    <thead>
        <tr style="background:#f4f4f4;">
            <th>Plan</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contracts as $contract): ?>
            <tr>
                <td><?= htmlspecialchars($contract['plan']) ?></td>
                <td><?= htmlspecialchars($contract['start_date']) ?></td>
                <td><?= htmlspecialchars($contract['end_date']) ?></td>
                <td><?= number_format($contract['amount'], 2) ?> €</td>
                <td><?= htmlspecialchars($contract['status']) ?></td>
                <td>
                    <a href="?company_id=<?= $companyId ?>&edit=<?= $contract['id'] ?>">Modifier</a> |
                    <a href="?company_id=<?= $companyId ?>&delete=<?= $contract['id'] ?>" onclick="return confirm('Supprimer ce contrat ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($contracts)): ?>
            <tr><td colspan="6">Aucun contrat enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
