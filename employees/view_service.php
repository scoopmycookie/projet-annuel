<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: services.php");
    exit();
}

$provider_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM providers WHERE id = ? AND is_verified = 1");
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $not_found = true;
} else {
    $provider = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du service</title>
    <link rel="stylesheet" href="../assets/css/employee.css">
</head>
<body>
<?php include '../includes/header_employees.php'; ?>

<main class="container">
    <?php if (isset($not_found)): ?>
        <h1>❌ Fournisseur introuvable ou non vérifié</h1>
        <a href="services.php" class="btn btn-blue">⬅ Retour aux services</a>
    <?php else: ?>
        <h1>🧾 <?= htmlspecialchars($provider['name']) ?></h1>

        <div style="margin-top: 20px;">
            <p><strong>Email :</strong> <?= htmlspecialchars($provider['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($provider['phone']) ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($provider['address']) ?></p>
            <?php if (!empty($provider['gender'])): ?>
                <p><strong>Genre :</strong> <?= htmlspecialchars($provider['gender']) ?></p>
            <?php endif; ?>
            <p><strong>Description :</strong><br>
                <?= nl2br(htmlspecialchars($provider['description'])) ?></p>
        </div>

        <a href="services.php" class="btn btn-blue" style="margin-top: 20px;">⬅ Retour aux services</a>
    <?php endif; ?>
</main>

<?php include '../includes/footer_employees.php'; ?>
</body>
</html>
