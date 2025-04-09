<?php
session_start();
require '../database/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Traitement formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone      = $_POST['phone'];
    $address    = $_POST['address'];
    $gender     = $_POST['gender'];
    $role       = $_POST['role'];
    $company    = $_POST['company'];

    $stmt = $conn->prepare("INSERT INTO users 
        (first_name, last_name, email, password, role, phone, address, company, gender, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())");

    $stmt->bind_param("sssssssss", 
        $first_name, $last_name, $email, $password, $role, $phone, $address, $company, $gender);

    if ($stmt->execute()) {
        header("Location: manage_users.php?success=Utilisateur ajouté avec succès");
        exit();
    } else {
        $error = "Erreur lors de l'ajout : " . $stmt->error;
    }
}

// Récupération des utilisateurs
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone, address, gender, role, company, status, created_at FROM users ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header_admin.php'; ?>

<main class="container">
    <h1>👥 Gestion des utilisateurs</h1>

    <?php if (isset($_GET['success'])) : ?>
        <p class="success-msg"><?= htmlspecialchars($_GET['success']); ?></p>
    <?php endif; ?>
    <?php if (isset($error)) : ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <section class="form-container">
        <h2>➕ Ajouter un utilisateur</h2>
        <form method="POST">
            <input type="hidden" name="add_user" value="1">

            <input type="text" name="first_name" placeholder="Prénom" required>
            <input type="text" name="last_name" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="tel" name="phone" placeholder="Téléphone" required>
            <input type="text" name="address" placeholder="Adresse" required>
            <input type="text" name="company" placeholder="Entreprise (facultatif)">
            
            <select name="gender" required>
                <option value="">Genre</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>

            <select name="role" required>
                <option value="">Rôle</option>
                <option value="admin">Administrateur</option>
                <option value="employee">Employé</option>
                <option value="supplier">Fournisseur</option>
                <option value="client">Client</option>
            </select>

            <button type="submit" class="btn btn-green">Ajouter</button>
        </form>
    </section>

    <!-- Liste des utilisateurs -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Prénom</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Adresse</th>
                    <th>Genre</th><th>Rôle</th><th>Entreprise</th><th>Statut</th><th>Inscription</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['first_name']); ?></td>
                        <td><?= htmlspecialchars($row['last_name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= htmlspecialchars($row['address']); ?></td>
                        <td><?= htmlspecialchars($row['gender']); ?></td>
                        <td><?= ucfirst($row['role']); ?></td>
                        <td><?= $row['company'] ?: 'Non renseigné'; ?></td>
                        <td>
                            <?php if ($row['status'] === 'active') : ?>
                                <span class="status-active">Active</span>
                            <?php elseif ($row['status'] === 'archived') : ?>
                                <span class="status-archived">Archivé</span>
                            <?php else : ?>
                                <span class="status-banned">Banni</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date("d/m/Y", strtotime($row['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn btn-orange">Modifier</a>
                            <?php if ($row['status'] === 'archived') : ?>
                                <a href="unarchive_user.php?id=<?= $row['id']; ?>" class="btn btn-green">Désarchiver</a>
                            <?php elseif ($row['status'] === 'banned') : ?>
                                <a href="unban_user.php?id=<?= $row['id']; ?>" class="btn btn-blue">Débannir</a>
                            <?php else : ?>
                                <a href="archive_user.php?id=<?= $row['id']; ?>" class="btn btn-yellow">Archiver</a>
                                <a href="ban_user.php?id=<?= $row['id']; ?>" class="btn btn-red">Bannir</a>
                            <?php endif; ?>
                            <a href="delete_user.php?id=<?= $row['id']; ?>" class="btn btn-red" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
</body>
</html>
