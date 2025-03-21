<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Récupérer toutes les entreprises
$companies_query = "SELECT id, name, email, phone, address, created_at, status FROM companies ORDER BY created_at DESC";
$companies_result = $conn->query($companies_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les entreprises</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_admin.php'; ?>

    <main class="container">
        <h1>🏢 Gestion des entreprises</h1>

        <!-- Formulaire d'ajout d'une entreprise -->
        <section class="form-container">
            <h2>➕ Ajouter une entreprise</h2>
            <form action="add_company.php" method="POST">
                <input type="text" name="name" placeholder="Nom de l'entreprise" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Téléphone" required>
                <input type="text" name="address" placeholder="Adresse" required>
                <button type="submit" class="btn btn-green">Ajouter</button>
            </form>
        </section>

        <!-- Tableau des entreprises -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($company = $companies_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($company['id']); ?></td>
                            <td><?php echo htmlspecialchars($company['name']); ?></td>
                            <td><?php echo htmlspecialchars($company['email']); ?></td>
                            <td><?php echo htmlspecialchars($company['phone']); ?></td>
                            <td><?php echo htmlspecialchars($company['address']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($company['created_at'])); ?></td>
                            <td>
                                <?php if ($company['status'] === 'active'): ?>
                                    <span class="status-active">Active</span>
                                <?php elseif ($company['status'] === 'archived'): ?>
                                    <span class="status-archived">Archivée</span>
                                <?php else: ?>
                                    <span class="status-banned">Bannie</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <a href="edit_company.php?id=<?php echo $company['id']; ?>" class="btn btn-orange">Modifier</a>
                                <?php if ($company['status'] === 'archived'): ?>
                                    <a href="unarchive_company.php?id=<?php echo $company['id']; ?>" class="btn btn-green">Désarchiver</a>
                                <?php else: ?>
                                    <a href="archive_company.php?id=<?php echo $company['id']; ?>" class="btn btn-yellow">Archiver</a>
                                <?php endif; ?>
                                <?php if ($company['status'] === 'banned'): ?>
                                    <a href="unban_company.php?id=<?php echo $company['id']; ?>" class="btn btn-blue">Débannir</a>
                                <?php else: ?>
                                    <a href="ban_company.php?id=<?php echo $company['id']; ?>" class="btn btn-red">Bannir</a>
                                <?php endif; ?>
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
