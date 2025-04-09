<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérification du rôle
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

// Récupération de l'entreprise
$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
$company = $data['company'];

// Récupération des collaborateurs
$collab_stmt = $conn->prepare("SELECT * FROM users WHERE company = ? AND role = 'employee'");
$collab_stmt->bind_param("s", $company);
$collab_stmt->execute();
$collab_result = $collab_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des collaborateurs</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
</head>
<body>
<?php include '../includes/header_clients.php'; ?>

<main class="container">
    <h1>👥 Vos collaborateurs</h1>

    <a href="add_employee.php" class="btn btn-green">➕ Ajouter un collaborateur</a>

    <?php if (isset($_GET['success'])) : ?>
        <p class="success-msg"><?= htmlspecialchars($_GET['success']) ?></p>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Genre</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($emp = $collab_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                        <td><?= htmlspecialchars($emp['email']) ?></td>
                        <td><?= htmlspecialchars($emp['phone']) ?></td>
                        <td><?= htmlspecialchars($emp['address']) ?></td>
                        <td><?= htmlspecialchars($emp['gender']) ?></td>
                        <td><?= date('d/m/Y', strtotime($emp['created_at'])) ?></td>
                        <td class="action-buttons">
                            <a href="edit_employee.php?id=<?= $emp['id'] ?>" class="btn btn-orange">✏️ Modifier</a>
                            <a href="delete_employee.php?id=<?= $emp['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer ce collaborateur ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer_clients.php'; ?>
</body>
</html>
