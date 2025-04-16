<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}


$companies = $conn->query("SELECT id, name FROM companies WHERE status = 'active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'];
    $plan = $_POST['plan'];

    
    switch ($plan) {
        case 'starter':
            $price_per_employee = 180;
            break;
        case 'basic':
            $price_per_employee = 150;
            break;
        case 'premium':
            $price_per_employee = 100;
            break;
        default:
            $price_per_employee = 0;
    }

    $stmt = $conn->prepare("INSERT INTO quotes (company_id, plan, price_per_employee) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $company_id, $plan, $price_per_employee);

    if ($stmt->execute()) {
        header("Location: manage_quotes.php");
        exit();
    } else {
        $error = "Erreur lors de la création du devis : " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un devis</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>Ajouter un devis</h1>

    <?php if (isset($error)) : ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="add_quote.php" method="POST">
        <label for="company_id">Entreprise</label>
        <select id="company_id" name="company_id" required>
            <option value="">-- Sélectionnez une entreprise --</option>
            <?php while ($company = $companies->fetch_assoc()) : ?>
                <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="plan">Plan</label>
        <select id="plan" name="plan" required>
            <option value="starter">Starter (180€/an/salarié)</option>
            <option value="basic">Basic (150€/an/salarié)</option>
            <option value="premium">Premium (100€/an/salarié)</option>
        </select>

        <button type="submit" class="btn btn-green">Créer le devis</button>
    </form>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
