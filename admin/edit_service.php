<?php
session_start();
require '../database/database.php';

if (!isset($_GET['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: manage_services.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$service = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("UPDATE services SET title = ?, description = ?, price = ?, status = ?, start_date = ?, end_date = ? WHERE id = ?");
    $stmt->bind_param("ssdsssi", $title, $description, $price, $status, $start_date, $end_date, $id);
    $stmt->execute();

    header("Location: manage_services.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un service</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>✏ Modifier le service</h1>
    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="title" value="<?= htmlspecialchars($service['title']) ?>" required>

        <label>Description :</label>
        <textarea name="description" required><?= htmlspecialchars($service['description']) ?></textarea>

        <label>Prix (€) :</label>
        <input type="number" step="0.01" name="price" value="<?= $service['price'] ?>" required>

        <label>Date de début :</label>
        <input type="date" name="start_date" value="<?= $service['start_date'] ?>" required>

        <label>Date de fin :</label>
        <input type="date" name="end_date" value="<?= $service['end_date'] ?>" required>

        <label>Statut :</label>
        <select name="status">
            <option value="à venir" <?= $service['status'] === 'à venir' ? 'selected' : '' ?>>À venir</option>
            <option value="en cours" <?= $service['status'] === 'en cours' ? 'selected' : '' ?>>En cours</option>
            <option value="terminé" <?= $service['status'] === 'terminé' ? 'selected' : '' ?>>Terminé</option>
        </select>

        <button type="submit" class="btn btn-green">Mettre à jour</button>
    </form>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
