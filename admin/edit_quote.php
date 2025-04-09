<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_quotes.php");
    exit();
}

$id = $_GET['id'];

// Récupérer le devis
$stmt = $conn->prepare("SELECT * FROM quotes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$quote = $result->fetch_assoc();

// Récupérer les entreprises
$companies = $conn->query("SELECT id, name FROM companies ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = $_POST['company'];
    $plan = $_POST['plan'];

    $price_per_employee = match ($plan) {
        'starter' => 180,
        'basic' => 150,
        'premium' => 100,
        default => 0,
    };

    $update = $conn->prepare("UPDATE quotes SET company = ?, plan = ?, price_per_employee = ? WHERE id = ?");
    $update->bind_param("isdi", $company, $plan, $price_per_employee, $id);

    if ($update->execute()) {
        header("Location: manage_quotes.php");
        exit();
    } else {
        $error = "Erreur lors de la mise à jour.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le devis</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📝 Modifier le devis</h1>

    <?php if (isset($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="company">Entreprise</label>
        <select name="company" required>
            <?php while ($comp = $companies->fetch_assoc()): ?>
                <option value="<?= $comp['id'] ?>" <?= $comp['id'] == $quote['company'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($comp['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="plan">Formule</label>
        <select name="plan" required>
            <option value="starter" <?= $quote['plan'] === 'starter' ? 'selected' : '' ?>>Starter (180€)</option>
            <option value="basic" <?= $quote['plan'] === 'basic' ? 'selected' : '' ?>>Basic (150€)</option>
            <option value="premium" <?= $quote['plan'] === 'premium' ? 'selected' : '' ?>>Premium (100€)</option>
        </select>

        <button type="submit" class="btn btn-yellow">Mettre à jour</button>
        <a href="manage_quotes.php" class="btn">Annuler</a>
    </form>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
