<?php
session_start();
require_once('../db/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// 📌 Récupération des entreprises pour lier une activité à une entreprise
$sql = "SELECT id, name FROM companies";
$stmt = $pdo->query($sql);
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 📌 Ajouter une activité
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_activity'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $company_id = $_POST['company_id'] ?: NULL;

    $sql = "INSERT INTO activities (name, description, date, company_id) VALUES (:name, :description, :date, :company_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':date' => $date,
        ':company_id' => $company_id
    ]);

    header('Location: manage_activities.php');
    exit();
}

// 📌 Supprimer une activité
if (isset($_GET['delete'])) {
    $activityId = $_GET['delete'];
    $sql = "DELETE FROM activities WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $activityId]);

    header('Location: manage_activities.php');
    exit();
}

// 📌 Récupération des activités
$sql = "SELECT activities.*, companies.name AS company_name FROM activities 
        LEFT JOIN companies ON activities.company_id = companies.id";
$stmt = $pdo->query($sql);
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="main-content">
    <h1>Gestion des Activités</h1>

    <!-- 🔹 Formulaire d'ajout -->
    <div class="form-container">
        <h2>Ajouter une Activité</h2>
        <form action="manage_activities.php" method="POST">
            <label>Nom</label>
            <input type="text" name="name" required>

            <label>Description</label>
            <textarea name="description" rows="4"></textarea>

            <label>Date</label>
            <input type="date" name="date" required>

            <label>Entreprise (Optionnel)</label>
            <select name="company_id">
                <option value="">Aucune</option>
                <?php foreach ($companies as $company): ?>
                    <option value="<?= $company['id']; ?>"><?= htmlspecialchars($company['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="add_activity" class="btn">Ajouter</button>
        </form>
    </div>

    <!-- 🔹 Tableau des activités -->
    <h2>Liste des Activités</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Entreprise</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?= $activity['id']; ?></td>
                        <td><?= htmlspecialchars($activity['name']); ?></td>
                        <td><?= htmlspecialchars($activity['description']); ?></td>
                        <td><?= htmlspecialchars($activity['date']); ?></td>
                        <td><?= $activity['company_name'] ? htmlspecialchars($activity['company_name']) : 'Aucune'; ?></td>
                        <td>
                            <a href="edit_activity.php?id=<?= $activity['id']; ?>" class="btn-edit">Modifier</a>
                            <a href="manage_activities.php?delete=<?= $activity['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer cette activité ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
