<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 🔐 Vérifie que l'utilisateur est un employé
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../public/login.php");
    exit();
}

// 🔍 Récupération des fournisseurs validés
$stmt = $conn->prepare("SELECT id, name, description FROM providers WHERE is_verified = 1 ORDER BY name ASC");
$stmt->execute();
$services = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Services des fournisseurs</title>
    <link rel="stylesheet" href="../assets/css/employee.css">
</head>
<body>
<?php include '../includes/header_employees.php'; ?>

<main class="container">
    <h1>📦 Services disponibles</h1>

    <?php if ($services->num_rows === 0): ?>
        <p>Aucun fournisseur vérifié n’est disponible pour le moment.</p>
    <?php else: ?>
        <div class="service-list">
            <?php while ($row = $services->fetch_assoc()): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($row['name']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <a class="btn btn-blue" href="view_service.php?id=<?= $row['id'] ?>">Voir le service</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer_employees.php'; ?>
</body>
</html>
