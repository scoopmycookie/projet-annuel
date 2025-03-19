<?php
session_start();
require '../database/database.php';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Vérifier si l'ID de l'utilisateur à modifier est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Utilisateur non spécifié.");
}

$user_id = $_GET['id'];

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, address, company, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Utilisateur introuvable.");
}

$user = $result->fetch_assoc();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si le formulaire a bien été soumis
    if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['company'], $_POST['role'])) {
        die("Tous les champs sont requis.");
    }

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $company = trim($_POST['company']);
    $role = $_POST['role'];

    // Vérifier si l'email est déjà utilisé par un autre utilisateur
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $check_email = $stmt->get_result();

    if ($check_email->num_rows > 0) {
        $error = "Cet email est déjà utilisé par un autre utilisateur.";
    } else {
        // Mettre à jour les informations de l'utilisateur
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, company = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $phone, $address, $company, $role, $user_id);

        if ($stmt->execute()) {
            $success = "Utilisateur mis à jour avec succès.";
            // Rediriger vers la page de gestion des utilisateurs après la mise à jour
            header("Location: manage_users.php");
            exit();
        } else {
            $error = "Erreur SQL : " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header_admin.php'; ?>

<main>
    <section class="container">
        <h1>Modifier l'utilisateur</h1>

        <?php if (isset($success)) : ?>
            <p class="success-msg"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="first_name">Prénom :</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

            <label for="last_name">Nom :</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="phone">Téléphone :</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <label for="address">Adresse :</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">

            <label for="company">Entreprise :</label>
            <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($user['company']); ?>">

            <label for="role">Rôle :</label>
            <select id="role" name="role">
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="employee" <?php echo ($user['role'] == 'employee') ? 'selected' : ''; ?>>Employé</option>
                <option value="supplier" <?php echo ($user['role'] == 'supplier') ? 'selected' : ''; ?>>Fournisseur</option>
                <option value="client" <?php echo ($user['role'] == 'client') ? 'selected' : ''; ?>>Client</option>
            </select>

            <button type="submit" class="btn">Mettre à jour</button>
        </form>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>

</body>
</html>
