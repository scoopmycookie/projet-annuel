<?php
session_start();
require_once('../db/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// 🔹 Récupération des utilisateurs
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 Ajouter un utilisateur (Formulaire soumis)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Insertion dans la base de données
    $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role
    ]);

    // Rafraîchir la page pour voir le nouvel utilisateur
    header('Location: manage_users.php');
    exit();
}

// 🔹 Supprimer un utilisateur
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $userId]);

    // Rafraîchir la page
    header('Location: manage_users.php');
    exit();
}

include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="main-content">
    <h1>Gestion des Utilisateurs</h1>

    <!-- 🔹 Formulaire d'ajout d'utilisateur -->
    <div class="form-container">
        <h2>Ajouter un utilisateur</h2>
        <form action="manage_users.php" method="POST">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Rôle</label>
            <select name="role" id="role">
                <option value="admin">Admin</option>
                <option value="employe">Employé</option>
                <option value="prestataire">Prestataire</option>
                <option value="user">Utilisateur</option>
            </select>

            <button type="submit" name="add_user" class="btn">Ajouter l'utilisateur</button>
        </form>
    </div>

    <!-- 🔹 Tableau des utilisateurs -->
    <h2>Liste des Utilisateurs</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= ucfirst($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn-edit">Modifier</a>
                            <a href="manage_users.php?delete=<?= $user['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
