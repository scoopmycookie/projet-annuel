<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_providers.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM providers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$provider = $result->fetch_assoc();

if (!$provider) {
    echo "Fournisseur introuvable.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE providers SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $phone, $id);
    $stmt->execute();
    header("Location: manage_providers.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le fournisseur</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>✏️ Modifier le fournisseur</h1>
    <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($provider['name']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($provider['email']) ?>" required>
        <input type="text" name="phone" value="<?= htmlspecialchars($provider['phone']) ?>" required>
        <button type="submit" class="btn btn-orange">Mettre à jour</button>
    </form>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
