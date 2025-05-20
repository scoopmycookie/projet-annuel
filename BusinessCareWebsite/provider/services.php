<?php
require_once '../includes/db.php';
include 'includes/header.php';

$message = '';
$can_post = false;

// Vérification facture
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE company_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['company_id']]);
$invoice = $stmt->fetch();

if ($invoice && $invoice['status'] === 'paid') {
    $can_post = true;
}

// Suppression de service
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM services WHERE id = ? AND provider_id = ?")->execute([$id, $_SESSION['user_id']]);
    $message = "Service supprimé.";
}

// Ajout de service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_post) {
    $stmt = $pdo->prepare("INSERT INTO services (title, description, category, price, service_date, service_time, capacity, duration, provider_id)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['category'],
        $_POST['price'],
        $_POST['service_date'],
        $_POST['service_time'],
        $_POST['capacity'],
        $_POST['duration'],
        $_SESSION['user_id']
    ]);
    $message = "Service ajouté avec succès.";
}

$stmt = $pdo->prepare("SELECT * FROM services WHERE provider_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$services = $stmt->fetchAll();
?>

<main class="form-section">
    <h2>Calendrier & Gestion des Services</h2>

    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div style="display: flex; flex-wrap: wrap; gap: 30px;">
        <div style="flex: 1; min-width: 300px;">
            <h3>Mon calendrier</h3>
            <div id="calendar" style="max-width: 100%; height: auto;"></div>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <?php if (!$can_post): ?>
                <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 6px;">
                    Vous devez régler votre facture pour ajouter un service.
                </div>
            <?php else: ?>
                <form method="POST">
                    <input type="text" name="title" placeholder="Titre du service" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <textarea name="description" placeholder="Description" required style="width:100%;padding:10px;margin-bottom:10px;"></textarea>
                    <input type="text" name="category" placeholder="Catégorie" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <input type="number" step="0.01" name="price" placeholder="Prix (€)" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <input type="date" name="service_date" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <input type="time" name="service_time" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <input type="number" name="capacity" placeholder="Capacité" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <input type="number" name="duration" placeholder="Durée (min)" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <button type="submit" class="cta-button">Ajouter le service</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($services): ?>
        <h3 style="margin-top:40px;">Mes services</h3>
        <ul>
            <?php foreach ($services as $srv): ?>
                <li>
                    <strong><?= htmlspecialchars($srv['title']) ?></strong>
                    (<?= $srv['service_date'] ?> à <?= $srv['service_time'] ?>)
                    - <a href="?delete=<?= $srv['id'] ?>" onclick="return confirm('Supprimer ce service ?')">Supprimer</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        height: 'auto',
        contentHeight: 500,
        aspectRatio: 1.5,
        events: [
            <?php foreach ($services as $srv): ?>
            {
                title: "<?= htmlspecialchars($srv['title']) ?>",
                start: "<?= $srv['service_date'] . 'T' . $srv['service_time'] ?>",
                allDay: false
            },
            <?php endforeach; ?>
        ],
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        }
    });
    calendar.render();
});
</script>

<?php include 'includes/footer.php'; ?>
