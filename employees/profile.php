<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Vérifie que l'utilisateur est un employé connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 🔍 Récupère les données de l'employé
$stmt = $conn->prepare("SELECT u.*, c.name AS company_name 
                        FROM users u
                        LEFT JOIN companies c ON u.company = c.id
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $password = $_POST['password'] ?? '';

    // Mise à jour de base
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, address = ?, gender = ?, password = ? WHERE id = ?");
        $update->bind_param("ssssssi", $first_name, $last_name, $phone, $address, $gender, $hashed_password, $user_id);
    } else {
        $update = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, address = ?, gender = ? WHERE id = ?");
        $update->bind_param("sssssi", $first_name, $last_name, $phone, $address, $gender, $user_id);
    }

    if ($update->execute()) {
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $success = "Profil mis à jour avec succès.";
    } else {
        $error = "Erreur SQL : " . $update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <link rel="stylesheet" href="../assets/css/employee.css">
</head>
<body>
<?php include '../includes/header_employees.php'; ?>

<main class="container">
    <h1>👤 Mon profil</h1>

    <?php if (isset($success)) : ?>
        <p class="success-msg" style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php elseif (isset($error)) : ?>
        <p class="error-msg" style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Prénom</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

        <label>Nom</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

        <label>Email (non modifiable)</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

        <label>Téléphone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">

        <label>Adresse</label>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>">

        <label>Genre</label>
        <select name="gender">
            <option value="Homme" <?= $user['gender'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
            <option value="Femme" <?= $user['gender'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
        </select>

        <label>Mot de passe (laisser vide pour ne pas changer)</label>
        <input type="password" name="password">

        <label>Entreprise</label>
        <input type="text" value="<?= htmlspecialchars($user['company_name']) ?>" disabled>

        <button type="submit" class="btn btn-green">💾 Mettre à jour</button>
    </form>
</main>

<?php include '../includes/footer_employees.php'; ?>
</body>
</html>
