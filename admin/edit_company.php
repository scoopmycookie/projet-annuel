<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Aucune entreprise sélectionnée.");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Entreprise introuvable.");
}

$company = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($status)) {
        die("Tous les champs sont obligatoires.");
    }

    $update_stmt = $conn->prepare("UPDATE companies SET name=?, email=?, phone=?, address=?, status=? WHERE id=?");
    $update_stmt->bind_param("sssssi", $name, $email, $phone, $address, $status, $id);

    if ($update_stmt->execute()) {
        header("Location: manage_companies.php?success=Entreprise mise à jour avec succès");
        exit();
    } else {
        die("Erreur lors de la mise à jour : " . $update_stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'entreprise</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_admin.php'; ?>

<main class="container">
    <h1>✏ Modifier l'entreprise</h1>

    <form action="edit_company.php?id=<?php echo $id; ?>" method="POST">
        <label>Nom de l'entreprise :</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($company['name']); ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($company['email']); ?>" required>

        <label>Téléphone :</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($company['phone']); ?>" required>

        <label>Adresse :</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($company['address']); ?>" required>

        <label>Statut :</label>
        <select name="status" required>
            <option value="active" <?php if ($company['status'] === 'active') echo 'selected'; ?>>Active</option>
            <option value="archived" <?php if ($company['status'] === 'archived') echo 'selected'; ?>>Archivée</option>
            <option value="banned" <?php if ($company['status'] === 'banned') echo 'selected'; ?>>Bannie</option>
        </select>

        <button type="submit" class="btn btn-green">Enregistrer les modifications</button>
    </form>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
