<?php
session_start();
require_once('../db/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Récupération des employés
$sql = "SELECT * FROM users WHERE role = 'employe'";
$stmt = $pdo->query($sql);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="main-content">
    <h1>Gestion des Salariés</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= $employee['id']; ?></td>
                        <td><?= htmlspecialchars($employee['name']); ?></td>
                        <td><?= htmlspecialchars($employee['email']); ?></td>
                        <td>
                            <a href="edit_employee.php?id=<?= $employee['id']; ?>" class="btn-edit">Modifier</a>
                            <a href="manage_employees.php?delete=<?= $employee['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ce salarié ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
