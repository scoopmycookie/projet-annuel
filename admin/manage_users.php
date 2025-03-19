<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Récupérer tous les utilisateurs avec leur entreprise et statut (actif, archivé, banni)
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, role, company, status FROM users ORDER BY role DESC");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #1f1f1f;
            color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #444;
        }
        th {
            background: #ff9800;
            color: #121212;
        }
        tr:hover {
            background: #292929;
        }
        .btn-warning, .btn-danger, .btn {
            display: inline-block;
            width: 100px;
            text-align: center;
        }
        .btn-warning {
            background: #ffcc00;
            color: black;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-warning:hover {
            background: #e6b800;
        }
        .btn-danger {
            background: #ff4b4b;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-danger:hover {
            background: #e63939;
        }
        .archived {
            background: rgba(255, 204, 0, 0.2);
        }
        .banned {
            background: rgba(255, 75, 75, 0.2);
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../includes/header_admin.php'; ?>

    <main>
        <section class="admin-dashboard">
            <div class="container">
                <h1>Gestion des utilisateurs</h1>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Entreprise</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="<?php echo ($user['status'] == 'archived') ? 'archived' : (($user['status'] == 'banned') ? 'banned' : ''); ?>">
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo htmlspecialchars($user['company'] ?: 'Non renseigné'); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($user['status'])); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn">Modifier</a>
                                    <a href="archive_user.php?id=<?php echo $user['id']; ?>" class="btn-warning" onclick="return confirm('Voulez-vous vraiment archiver cet utilisateur ?');">Archiver</a>
                                    <a href="ban_user.php?id=<?php echo $user['id']; ?>" class="btn-danger" onclick="return confirm('Voulez-vous vraiment bannir cet utilisateur ?');">Bannir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include '../includes/footer_admin.php'; ?>
</body>
</html>