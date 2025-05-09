<?php
require_once '../includes/db.php';
include 'includes/header.php';

$errors = [];
$success = false;

// Récupération des entreprises
$companies = $pdo->query("SELECT id, name FROM companies ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = (int) ($_POST['company_id'] ?? 0);
    $amount     = $_POST['amount'] ?? '';
    $due_date   = $_POST['due_date'] ?? null;

    // Validation
    if ($company_id <= 0) $errors[] = "Entreprise invalide.";
    if (!is_numeric($amount) || $amount <= 0) $errors[] = "Montant invalide.";
    if (!empty($due_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due_date)) $errors[] = "Date invalide.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO invoices (company_id, amount, status, due_date)
            VALUES (?, ?, 'unpaid', ?)
        ");
        $stmt->execute([$company_id, $amount, $due_date]);
        $success = true;
    }
}
?>

<section class="form-section">
    <h2>➕ Ajouter une facture</h2>

    <?php if ($success): ?>
        <p style="color: green;">Facture enregistrée avec succès.</p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label for="company_id">Entreprise :</label><br>
        <select name="company_id" id="company_id" required style="width: 100%; padding: 8px;">
            <option value="">-- Sélectionner une entreprise --</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="amount">Montant (€) :</label><br>
        <input type="number" step="0.01" name="amount" id="amount" required style="width: 100%; padding: 8px;"><br><br>

        <label for="due_date">Date limite de paiement :</label><br>
        <input type="date" name="due_date" id="due_date" style="width: 100%; padding: 8px;"><br><br>

        <button type="submit" style="padding: 10px 20px;">Enregistrer la facture</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
