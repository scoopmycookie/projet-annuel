<?php
require_once '../includes/db.php';
include 'includes/header.php';

session_start();
$provider_id = $_SESSION['user_id']; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO provider_availability (provider_id, available_date, start_time, end_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $provider_id,
        $_POST['date'],
        $_POST['start_time'],
        $_POST['end_time']
    ]);

    $service_stmt = $pdo->prepare("INSERT INTO services (title, service_date, service_time, price, capacity, duration, description, category, provider_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $service_stmt->execute([
        $_POST['title'],
        $_POST['date'],
        $_POST['start_time'],
      $_POST['price'],
        $_POST['capacity'],
        $_POST['duration'],
        $_POST['description'],
        $_POST['category'],
        $provider_id
    ]);

    header("Location: provider_availability.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM provider_availability WHERE id = ? AND provider_id = ?");
    $stmt->execute([$_GET['delete'], $provider_id]);
    header("Location: provider_availability.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM provider_availability WHERE provider_id = ? ORDER BY available_date, start_time");
$stmt->execute([$provider_id]);
$slots = $stmt->fetchAll();

$services_stmt = $pdo->prepare("SELECT * FROM services WHERE provider_id = ? ORDER BY service_date, service_time");
$services_stmt->execute([$provider_id]);
$services = $services_stmt->fetchAll();
?>

<style>
    .availability-container {
        max-width: 800px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .availability-container h2 {
        margin-bottom: 20px;
    }
    form input, form textarea {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table th, table td {
        padding: 10px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    table tr:hover {
        background: #f9f9f9;
    }
    .tag-group {
        background-color: #e0f7fa;
        padding: 3px 6px;
        border-radius: 5px;
        font-size: 0.85em;
        color: #00796b;
    }
    .tag-indiv {
        background-color: #fce4ec;
        padding: 3px 6px;
        border-radius: 5px;
        font-size: 0.85em;
        color: #c2185b;
    }
</style>

<div class="availability-container">
    <h2>Mes disponibilités & Création de rendez-vous</h2>
    <form method="post">
        <label for="date">Date :</label>
        <input type="date" name="date" id="date" required>

        <label for="start_time">Heure de début :</label>
        <input type="time" name="start_time" id="start_time" required>

        <label for="end_time">Heure de fin :</label>
        <input type="time" name="end_time" id="end_time" required>

        <label for="title">Titre du service :</label>
        <input type="text" name="title" id="title" required>

        <label for="category">Catégorie :</label>
        <input type="text" name="category" id="category" required>

        <label for="description">Description :</label>
        <textarea name="description" id="description"></textarea>

        <label for="price">Prix (€) :</label>
        <input type="number" step="0.01" name="price" id="price" required>

        <label for="capacity">Capacité :</label>
        <input type="number" name="capacity" id="capacity" required>

        <label for="duration">Durée (minutes) :</label>
        <input type="number" name="duration" id="duration" value="60" required>

        <input type="submit" value="Ajouter">
    </form>

    <h3>Créneaux de disponibilité</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($slots as $slot): ?>
                <tr>
                    <td><?= htmlspecialchars($slot['available_date']) ?></td>
                    <td><?= htmlspecialchars($slot['start_time']) ?></td>
                    <td><?= htmlspecialchars($slot['end_time']) ?></td>
                    <td><a href="?delete=<?= $slot['id'] ?>" onclick="return confirm('Supprimer ce créneau ?')">Supprimer</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Services programmés</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Titre</th>
                <th>Type</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $srv): ?>
                <tr>
                    <td><?= htmlspecialchars($srv['service_date']) ?></td>
                    <td><?= htmlspecialchars($srv['service_time']) ?></td>
                    <td><?= htmlspecialchars($srv['title']) ?></td>
                    <td><span class="<?= $srv['capacity'] > 1 ? 'tag-group' : 'tag-indiv' ?>">
                        <?= $srv['capacity'] > 1 ? 'Groupe' : 'Individuel' ?></span></td>
                    <td><?= number_format($srv['price'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>