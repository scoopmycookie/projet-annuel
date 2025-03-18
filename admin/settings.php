<?php
session_start();
require_once('../db/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// 📌 Récupération des infos de l'admin
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 📌 Mise à jour des informations de l'admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    
    // Vérifier si un mot de passe est renseigné
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':id' => $user_id
        ]);
    } else {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':id' => $user_id
        ]);
    }

    $_SESSION['name'] = $name;
    header('Location: settings.php?success=1');
    exit();
}

include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="main-content">
    <h1>Paramètres</h1>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">Modifications enregistrées avec succès !</p>
    <?php endif; ?>

    <div class="form-container">
        <h2>Modifier vos informations</h2>
        <form action="settings.php" method="POST">
            <label>Nom</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label>Nouveau Mot de Passe (Optionnel)</label>
            <input type="password" name="password" placeholder="Laisser vide pour ne pas changer">

            <button type="submit" name="update_profile" class="btn">Mettre à jour</button>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>
