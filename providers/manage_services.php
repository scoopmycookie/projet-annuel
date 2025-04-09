<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

// Récupération de l'entreprise du fournisseur
$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc()['company'];

// Récupération des services liés à cette entreprise
$services = $conn->prepare("SELECT * FROM services WHERE company = ?");
$services->bind_param("s", $company);
$services->execute();
$result = $services->get_result();
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
            <input type="number" name="price" placeholder="Prix (€)" required>
            <input type="date" name="start_date" required>
            <input type="date" name="end_date" required>
            <button type="submit" class="btn">Ajouter</button>
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
                <?php while ($service = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($service['title']) ?></td>
                        <td><?= htmlspecialchars($service['description']) ?></td>
                        <td><?= number_format($service['price'], 2) ?> €</td>
                        <td><?= $service['start_date'] ?></td>
                        <td><?= $service['end_date'] ?></td>
                        <td><?= ucfirst($service['status']) ?></td>
                        <td>
                            <a href="edit_service.php?id=<?= $service['id'] ?>" class="btn btn-orange">Modifier</a>
                            <a href="delete_service.php?id=<?= $service['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer ce service ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer_providers.php'; ?>
</body>
</html>
