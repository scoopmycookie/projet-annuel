<?php
require_once '../includes/db.php';
include 'includes/header.php';

$errors = [];

$companies = $pdo->query("SELECT id, name FROM companies ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname  = trim($_POST['firstname'] ?? '');
    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $position   = trim($_POST['position'] ?? '');
    $password   = $_POST['password'] ?? '';
    $company_id = !empty($_POST['company_id']) ? (int) $_POST['company_id'] : null;
    $role       = $_POST['role'] ?? '';
    $status     = 'approved';

    if ($firstname === '') $errors[] = "Le prénom est requis.";
    if ($name === '') $errors[] = "Le nom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if ($password === '') $errors[] = "Mot de passe requis.";
    if (!$company_id) $errors[] = "Entreprise invalide.";
    if (!in_array($role, ['employee', 'client', 'provider', 'admin'])) $errors[] = "Rôle invalide.";

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (firstname, name, email, phone, position, password, company_id, role, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$firstname, $name, $email, $phone, $position, $hashedPassword, $company_id, $role, $status]);
        header("Location: users.php?added=1");
        exit;
    }
}
?>

<section class="form-section">
    <h2>Ajouter un utilisateur</h2>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="" style="max-width: 600px;">
        <label for="firstname">Prénom :</label>
        <input type="text" id="firstname" name="firstname" required style="width: 100%; padding: 8px;">

        <label for="name" style="margin-top: 10px;">Nom :</label>
        <input type="text" id="name" name="name" required style="width: 100%; padding: 8px;">

        <label for="email" style="margin-top: 10px;">Email :</label>
        <input type="email" id="email" name="email" required style="width: 100%; padding: 8px;">

        <label for="phone" style="margin-top: 10px;">Téléphone :</label>
        <input type="text" id="phone" name="phone" style="width: 100%; padding: 8px;">

        <label for="position" style="margin-top: 10px;">Poste :</label>
        <input type="text" id="position" name="position" style="width: 100%; padding: 8px;">

        <label for="password" style="margin-top: 10px;">Mot de passe :</label>
        <input type="password" id="password" name="password" required style="width: 100%; padding: 8px;">

        <label for="company_id" style="margin-top: 10px;">Entreprise :</label>
        <select id="company_id" name="company_id" required style="width: 100%; padding: 8px;">
            <option value="">-- Sélectionner une entreprise --</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="role" style="margin-top: 10px;">Rôle :</label>
        <select id="role" name="role" required style="width: 100%; padding: 8px;">
            <option value="">-- Choisir un rôle --</option>
            <option value="employee">Employé</option>
            <option value="client">Client</option>
            <option value="provider">Prestataire</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" style="margin-top: 15px; padding: 10px 20px;">Ajouter</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
