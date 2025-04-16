<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
$company = $data['company'];

$services = $conn->prepare("SELECT title, description, status, start_date, end_date FROM services WHERE company = ? ORDER BY start_date DESC");
$services->bind_param("s", $company);
$services->execute();
$result = $services->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Services</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
</head>
<body>

<?php include '../includes/header_clients.php'; ?>

<main class="container">
    <h1>🛠 Mes Services</h1>
    <p>Liste des services souscrits pour l'entreprise <strong><?= htmlspecialchars($company) ?></strong>.</p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Début</th>
                    <th>Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($service = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['title']) ?></td>
                            <td><?= htmlspecialchars($service['description']) ?></td>
                            <td>
                                <?php
                                    $status = $service['status'];
                                    $badgeClass = match($status) {
                                        'à venir' => 'status-upcoming',
                                        'en cours' => 'status-active',
                                        'terminé' => 'status-ended',
                                        default => 'status-other'
                                    };
                                ?>
                                <span class="<?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                            </td>
                            <td><?= htmlspecialchars($service['start_date']) ?></td>
                            <td><?= htmlspecialchars($service['end_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun service trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer_clients.php'; ?>
</body>
</html>
