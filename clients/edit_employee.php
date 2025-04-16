<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
$company = $client['company'];

if (!isset($_GET['id'])) {
    die("ID de l'employé manquant.");
}

$emp_id = intval($_GET['id']);

// Récupération de l'employé à modifier
$emp_stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'employee' AND company = ?");
$emp_stmt->bind_param("is", $emp_id, $company);
$emp_stmt->execute();
$emp_result = $emp_stmt->get_result();

if ($emp_result->num_rows === 0) {
    die("Aucun collaborateur trouvé.");
}
$employee = $emp_result->fetch_assoc();

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];

    $update = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, address=?, gender=? WHERE id=? AND company=?");
    $update->bind_param("ssssssis", $first, $last, $email, $phone, $address, $gender, $emp_id, $company);

    if ($update->execute()) {
        header("Location: dashboard.php?success=Modifications enregistrées");
        exit();
    } else {
        $error = "Erreur SQL : " . $update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un collaborateur</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include '../includes/header_public.php'; ?>

<main class="container">
    <h1>✏️ Modifier un collaborateur</h1>

    <?php if (isset($error)) : ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Prénom</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required>

        <label>Nom</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>" required>

        <label>Téléphone</label>
        <input type="tel" name="phone" value="<?= htmlspecialchars($employee['phone']) ?>" required>

        <label>Adresse</label>
        <input type="text" name="address" value="<?= htmlspecialchars($employee['address']) ?>" required>

        <label>Genre</label>
        <select name="gender" required>
            <option value="Homme" <?= $employee['gender'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
            <option value="Femme" <?= $employee['gender'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
        </select>

        <button type="submit" class="btn btn-orange">Enregistrer</button>
    </form>

    <br><a href="dashboard.php" class="btn">⬅ Retour</a>
</main>

<?php include '../includes/footer_public.php'; ?>
</body>
</html>
