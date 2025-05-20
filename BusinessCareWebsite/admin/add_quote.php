<?php
require_once '../includes/db.php';
include 'includes/header.php';

$errors = [];
$success = false;

$companies = $pdo->query("SELECT id, name FROM companies ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = (int) ($_POST['company_id'] ?? 0);
    $amount     = $_POST['amount'] ?? '';

    if ($company_id <= 0) $errors[] = "Entreprise invalide.";
    if (!is_numeric($amount) || $amount <= 0) $errors[] = "Montant invalide.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO quotes (company_id, amount) VALUES (?, ?)");
        $stmt->execute([$company_id, $amount]);
        $success = true;
    }
}
?>

<section class="form-section">
    <h2>➕ Ajouter un devis</h2>

    <?php if ($success): ?>
        <p style="color: green;">Devis enregistré avec succès.</p>
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

        <button type="submit" style="padding: 10px 20px;">Enregistrer le devis</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
