<?php
session_start();
require '../database/database.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$companies = $conn->query("SELECT * FROM companies ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des entreprises</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>🏢 Gestion des entreprises</h1>

    <!-- Formulaire d'ajout -->
    <section class="form-container">
        <h2>➕ Ajouter une entreprise</h2>
        <form action="add_company.php" method="POST">
            <input type="text" name="name" placeholder="Nom de l'entreprise" required>
            <input type="text" name="address" placeholder="Adresse" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Téléphone" required>
            <button type="submit" class="btn btn-green">Ajouter</button>
        </form>
    </section>

    <!-- Liste des entreprises -->
    <div class="table-container">
        <h2>📋 Liste des entreprises</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Validée</th>
                    <th>Créée le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($company = $companies->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($company['name']) ?></td>
                        <td><?= htmlspecialchars($company['address']) ?></td>
                        <td><?= htmlspecialchars($company['email']) ?></td>
                        <td><?= htmlspecialchars($company['phone']) ?></td>
                        <td>
                            <span class="status-<?= $company['status'] ?>"><?= ucfirst($company['status']) ?></span>
                        </td>
                        <td>
                            <!-- Affichage du statut de validation -->
                            <span class="status-<?= $company['is_verified'] ? 'verified' : 'unverified' ?>">
                                <?= $company['is_verified'] ? 'Validée' : 'Non validée' ?>
                            </span>
                        </td>
                        <td><?= date("d/m/Y", strtotime($company['created_at'])) ?></td>
                        <td class="action-buttons">
                            <!-- Modifier -->
                            <a href="edit_company.php?id=<?= $company['id'] ?>" class="btn btn-orange">Modifier</a>

                            <!-- Archiver / Désarchiver -->
                            <?php if ($company['status'] === 'archived'): ?>
                                <a href="unarchive_company.php?id=<?= $company['id'] ?>" class="btn btn-green">Désarchiver</a>
                            <?php elseif ($company['status'] === 'banned'): ?>
                                <a href="unban_company.php?id=<?= $company['id'] ?>" class="btn btn-green">Débannir</a>
                            <?php else: ?>
                                <a href="archive_company.php?id=<?= $company['id'] ?>" class="btn btn-yellow" onclick="return confirm('Archiver cette entreprise ?')">Archiver</a>
                                <a href="ban_company.php?id=<?= $company['id'] ?>" class="btn btn-darkred" onclick="return confirm('Bannir cette entreprise ?')">Bannir</a>
                            <?php endif; ?>

                            <!-- Validation / Invalidation -->
                            <?php if ($company['is_verified'] == 0): ?>
                                <a href="validate_company.php?id=<?= $company['id'] ?>&action=approve" class="btn btn-green">Valider</a>
                            <?php else: ?>
                                <a href="validate_company.php?id=<?= $company['id'] ?>&action=reject" class="btn btn-red">Invalider</a>
                            <?php endif; ?>

                            <!-- Supprimer -->
                            <a href="delete_company.php?id=<?= $company['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer cette entreprise ?')">Supprimer</a>
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
