<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['subscribe'])) {
    $service_id = intval($_GET['subscribe']);

    $check = $conn->prepare("SELECT * FROM service_registrations WHERE user_id = ? AND service_id = ?");
    $check->bind_param("ii", $user_id, $service_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO service_registrations (user_id, service_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $service_id);
        $insert->execute();
        $success = "Inscription réussie au service.";
    } else {
        $error = "Vous êtes déjà inscrit à ce service.";
    }
}

$services = $conn->query("SELECT services.*, providers.name AS fournisseur 
                          FROM services 
                          LEFT JOIN users ON services.company = users.company 
                          LEFT JOIN providers ON users.company = providers.name 
                          WHERE services.status = 'en cours'");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Services disponibles</title>
    <link rel="stylesheet" href="../assets/css/employee.css">
</head>
<body>
<?php include '../includes/header_employees.php'; ?>

<main class="container">
    <h1>🛠 Services disponibles</h1>

    <?php if (isset($success)) : ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)) : ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Fournisseur</th>
                    <th>Prix</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $services->fetch_assoc()) : ?>
                    <tr>
                        <td data-label="Titre"><?= htmlspecialchars($service['title']) ?></td>
                        <td data-label="Description"><?= htmlspecialchars($service['description']) ?></td>
                        <td data-label="Fournisseur"><?= htmlspecialchars($service['fournisseur']) ?></td>
                        <td data-label="Prix"><?= number_format($service['price'], 2) ?> €</td>
                        <td data-label="Date"><?= $service['start_date'] ?> → <?= $service['end_date'] ?></td>
                        <td data-label="Action">
                            <a href="?subscribe=<?= $service['id'] ?>" class="btn">S'inscrire</a>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>


<?php include '../includes/footer_employees.php'; ?>
</body>
</html>
