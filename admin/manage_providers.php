<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Ajouter un fournisseur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_provider'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO providers (name, email, phone, address, description, is_verified) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $description, $is_verified);
    $stmt->execute();
    header("Location: manage_providers.php?success=1");
    exit();
}

// Supprimer un fournisseur
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM providers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_providers.php?deleted=1");
    exit();
}

// Valider un fournisseur
if (isset($_GET['validate'])) {
    $id = $_GET['validate'];
    $stmt = $conn->prepare("UPDATE providers SET is_verified = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_providers.php?validated=1");
    exit();
}

// Archiver un fournisseur
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];
    $stmt = $conn->prepare("UPDATE providers SET is_verified = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_providers.php?archived=1");
    exit();
}

// Récupérer les fournisseurs
$providers = $conn->query("SELECT * FROM providers ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Fournisseurs</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📦 Gestion des fournisseurs</h1>

    <section class="form-container">
        <h2>➕ Ajouter un fournisseur</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Nom du fournisseur" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Téléphone" required>
            <input type="text" name="address" placeholder="Adresse" required>
            <textarea name="description" placeholder="Description" rows="4" required></textarea>

            <label>
                <input type="checkbox" name="is_verified"> Fournisseur validé
            </label>

            <button type="submit" name="add_provider" class="btn btn-green">Ajouter</button>
        </form>
    </section>

    <section class="table-container">
        <h2>📋 Liste des fournisseurs</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Description</th>
                    <th>Date de création</th>
                    <th>Validation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($provider = $providers->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($provider['name']) ?></td>
                        <td><?= htmlspecialchars($provider['email']) ?></td>
                        <td><?= htmlspecialchars($provider['phone']) ?></td>
                        <td><?= htmlspecialchars($provider['address']) ?></td>
                        <td><?= htmlspecialchars($provider['description']) ?></td>
                        <td><?= date("d/m/Y", strtotime($provider['created_at'])) ?></td>
                        <td>
                            <?php if ($provider['is_verified'] == 1): ?>
                                <span class="status-active">Validé</span>
                            <?php else: ?>
                                <span class="status-archived">En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_provider.php?id=<?= $provider['id'] ?>" class="btn btn-orange">Modifier</a>

                            <?php if ($provider['is_verified'] == 0): ?>
                                <a href="manage_providers.php?validate=<?= $provider['id'] ?>" class="btn btn-green">Valider</a>
                            <?php else: ?>
                                <a href="manage_providers.php?archive=<?= $provider['id'] ?>" class="btn btn-yellow">Archiver</a>
                            <?php endif; ?>

                            <a href="manage_providers.php?delete=<?= $provider['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer ce fournisseur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
