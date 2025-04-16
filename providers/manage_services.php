<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM services WHERE company = ?");
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Services</title>
    <link rel="stylesheet" href="../assets/css/providers.css">
</head>
<body>

<?php include '../includes/header_providers.php'; ?>

<main class="container">
    <h1>🛠 Gestion de mes services</h1>

    <section>
        <h2>➕ Ajouter un nouveau service</h2>
        <form action="add_service.php" method="POST">
            <input type="text" name="title" placeholder="Titre du service" required>
            <textarea name="description" placeholder="Description" rows="3" required></textarea>
            <input type="number" name="price" placeholder="Prix (€)" required step="0.01" min="0">
            <input type="date" name="start_date" required>
            <input type="date" name="end_date" required>
            <button type="submit" class="btn btn-green">Ajouter</button>
        </form>
    </section>

    <h2>📋 Services existants</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="7">Aucun service enregistré.</td></tr>
                <?php else: ?>
                    <?php while ($service = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($service['title']) ?></td>
                            <td><?= nl2br(htmlspecialchars($service['description'])) ?></td>
                            <td><?= number_format($service['price'], 2) ?> €</td>
                            <td><?= htmlspecialchars($service['start_date']) ?></td>
                            <td><?= htmlspecialchars($service['end_date']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($service['status'])) ?></td>
                            <td>
                                <a href="edit_service.php?id=<?= $service['id'] ?>" class="btn btn-orange">✏️ Modifier</a>
                                <a href="delete_service.php?id=<?= $service['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer ce service ?')">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer_providers.php'; ?>
</body>
</html>
