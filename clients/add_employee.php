<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

//  Vérifie que l'utilisateur est un client connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

//  Récupération de l’ID de l’entreprise liée à ce client
$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
$company_id = $data['company'] ?? null;

if (!$company_id) {
    die("⚠️ Erreur : vous n’êtes lié à aucune entreprise. Contactez un administrateur.");
}

// Traitement du formulaire d’ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';

    //  Vérifie que l'email n'existe pas déjà
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $error = "❌ Cet email est déjà utilisé.";
    } else {
        // Insertion du collaborateur
        $insert = $conn->prepare("INSERT INTO users (
            first_name, last_name, email, password, phone, address, gender, role, company, status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'employee', ?, 'active', NOW())");

        $insert->bind_param("sssssssi", $first_name, $last_name, $email, $password, $phone, $address, $gender, $company_id);

        if ($insert->execute()) {
            header("Location: dashboard.php?success=Collaborateur ajouté avec succès");
            exit();
        } else {
            $error = "❌ Erreur SQL : " . $insert->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un collaborateur</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
</head>
<body>
<?php include '../includes/header_clients.php'; ?>

<main class="container">
    <h1>➕ Ajouter un collaborateur</h1>

    <?php if (isset($error)) : ?>
        <p class="error-msg" style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="add_employee.php" method="POST">
        <label for="first_name">Prénom</label>
        <input type="text" name="first_name" required>

        <label for="last_name">Nom</label>
        <input type="text" name="last_name" required>

        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="password">Mot de passe</label>
        <input type="password" name="password" required>

        <label for="phone">Téléphone</label>
        <input type="tel" name="phone" required>

        <label for="address">Adresse</label>
        <input type="text" name="address" required>

        <label for="gender">Genre</label>
        <select name="gender" required>
            <option value="">-- Sélectionner --</option>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
        </select>

        <button type="submit" class="btn btn-green">Ajouter</button>
    </form>

    <br>
    <a href="dashboard.php" class="btn">⬅ Retour au tableau de bord</a>
</main>

<?php include '../includes/footer_clients.php'; ?>
</body>
</html>
