<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$conn->query("UPDATE services SET status = 'terminé' WHERE end_date < CURDATE() AND status != 'terminé'");
$conn->query("UPDATE services SET status = 'en cours' WHERE start_date <= CURDATE() AND end_date >= CURDATE() AND status != 'en cours'");
$conn->query("UPDATE services SET status = 'à venir' WHERE start_date > CURDATE() AND status != 'à venir'");

$stmt = $conn->prepare("SELECT * FROM services ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des services</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>🛠 Gestion des services</h1>

    <section class="form-container">
        <h2>Ajouter un nouveau service</h2>
        <form action="add_service.php" method="POST">
            <input type="text" name="title" placeholder="Nom du service" required>
            <textarea name="description" placeholder="Description du service" required></textarea>
            <input type="number" step="0.01" name="price" placeholder="Prix (€)" required>

            <label>Date de début :</label>
            <input type="date" name="start_date" required>

            <label>Date de fin :</label>
            <input type="date" name="end_date" required>

            <select name="status" required>
                <option value="à venir">À venir</option>
                <option value="en cours">En cours</option>
                <option value="terminé">Terminé</option>
            </select>

            <button type="submit" class="btn btn-green">Ajouter</button>
        </form>
    </section>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix (€)</th>
                    <th>Statut</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['title']) ?></td>
                        <td><?= htmlspecialchars($service['description']) ?></td>
                        <td><?= number_format($service['price'], 2, ',', ' ') ?> €</td>
                        <td>
                            <?php if ($service['status'] === 'à venir'): ?>
                                <span class="status-archived">À venir</span>
                            <?php elseif ($service['status'] === 'en cours'): ?>
                                <span class="status-active">En cours</span>
                            <?php else: ?>
                                <span class="status-banned">Terminé</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($service['start_date']) ?></td>
                        <td><?= htmlspecialchars($service['end_date']) ?></td>
                        <td><?= date("d/m/Y", strtotime($service['created_at'])) ?></td>
                        <td class="action-buttons">
                            <a href="edit_service.php?id=<?= $service['id'] ?>" class="btn btn-orange">Modifier</a>
                            <a href="delete_service.php?id=<?= $service['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer ce service ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
