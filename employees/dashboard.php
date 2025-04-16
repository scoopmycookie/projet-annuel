<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];


$stmt = $conn->prepare("
    SELECT companies.name AS company_name
    FROM users 
    LEFT JOIN companies ON users.company = companies.id
    WHERE users.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
$company_name = $data['company_name'] ?? 'Entreprise inconnue';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Employé</title>
    <link rel="stylesheet" href="../assets/css/employee.css">
</head>
<body>
<?php include '../includes/header_employees.php'; ?>

<main class="container">
    <h1>👋 Bienvenue <?= htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) ?> !</h1>
    <p>Vous faites partie de l’entreprise <strong><?= htmlspecialchars($company_name) ?></strong>.</p>

    <section class="quick-links">
        <a href="services.php" class="btn btn-blue">🛠️ Voir les services fournisseurs</a>
        <a href="profile.php" class="btn btn-yellow">👤 Mon profil</a>
        <a href="logout.php" class="btn btn-red">🚪 Déconnexion</a>
    </section>
</main>

<?php include '../includes/footer_employees.php'; ?>
</body>
</html>
