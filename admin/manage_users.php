<?php
session_start();
require '../database/database.php';

// Activer le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone, address, gender, role, company, status, created_at FROM users ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header_admin.php'; ?>

<main>
    <section class="container">
        <h1>Gestion des utilisateurs</h1>

        <?php if (isset($_GET['success'])) : ?>
            <p class="success-msg"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Genre</th>
                        <th>Rôle</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo ucfirst($row['role']); ?></td>
                            <td><?php echo $row['company'] ? htmlspecialchars($row['company']) : 'Non renseigné'; ?></td>
                            <td>
                                <?php if ($row['status'] === 'active') : ?>
                                    <span class="status-active">Active</span>
                                <?php elseif ($row['status'] === 'archived') : ?>
                                    <span class="status-archived">Archivé</span>
                                <?php else : ?>
                                    <span class="status-banned">Banni</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
                            <td class="action-buttons">
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-orange">Modifier</a>

                                <?php if ($row['status'] === 'archived') : ?>
                                    <a href="unarchive_user.php?id=<?php echo $row['id']; ?>" class="btn btn-green">Désarchiver</a>
                                <?php elseif ($row['status'] === 'banned') : ?>
                                    <a href="unban_user.php?id=<?php echo $row['id']; ?>" class="btn btn-blue">Débannir</a>
                                <?php else : ?>
                                    <a href="archive_user.php?id=<?php echo $row['id']; ?>" class="btn btn-yellow">Archiver</a>
                                    <a href="ban_user.php?id=<?php echo $row['id']; ?>" class="btn btn-red">Bannir</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>

</body>
</html>
