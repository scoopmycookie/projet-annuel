<?php
require_once '../includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<p>Utilisateur introuvable.</p>";
    include 'includes/footer.php';
    exit;
}

$companies = $pdo->query("SELECT id, name FROM companies ORDER BY name")->fetchAll();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $company_id = !empty($_POST['company_id']) ? (int) $_POST['company_id'] : null;
    $role = $_POST['role'] ?? '';

    if ($firstname === '') $errors[] = "Le prénom est requis.";
    if ($name === '') $errors[] = "Le nom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (!$company_id) $errors[] = "Entreprise invalide.";
    if (!in_array($role, ['employee', 'client', 'provider', 'admin'])) $errors[] = "Rôle invalide.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE users SET firstname = ?, name = ?, email = ?, phone = ?, position = ?, company_id = ?, role = ?
            WHERE id = ?
        ");
        $stmt->execute([$firstname, $name, $email, $phone, $position, $company_id, $role, $id]);
        $success = true;
    }
}
?>

<section class="form-section">
    <h2>Modifier l'utilisateur</h2>

    <?php if ($success): ?>
        <p style="color: green;">Mise à jour effectuée avec succès.</p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label>Prénom :</label>
        <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>

        <label>Nom :</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Téléphone :</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">

        <label>Poste :</label>
        <input type="text" name="position" value="<?= htmlspecialchars($user['position']) ?>">

        <label>Entreprise :</label>
        <select name="company_id" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>" <?= $company['id'] == $user['company_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($company['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Rôle :</label>
        <select name="role" required>
            <option value="">-- Choisir un rôle --</option>
            <option value="employee" <?= $user['role'] === 'employee' ? 'selected' : '' ?>>Employé</option>
            <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
            <option value="provider" <?= $user['role'] === 'provider' ? 'selected' : '' ?>>Prestataire</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit" style="margin-top: 15px;">Enregistrer</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
