<?php
require_once '../includes/db.php';
include 'includes/header.php';

$month = isset($_GET['month']) ? (int) $_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfWeek = date('w', strtotime("$year-$month-01"));

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header("Location: services.php?month=$month&year=$year");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedDate = $_POST['date'];
    if (strtotime($selectedDate) >= strtotime(date('Y-m-d'))) {
        $stmt = $pdo->prepare("INSERT INTO services (title, service_date, service_time, price, capacity, duration, description, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'],
            $_POST['date'],
            $_POST['time'],
            $_POST['price'],
            $_POST['capacity'],
            $_POST['duration'],
            $_POST['description'],
            $_POST['category']
        ]);
        header("Location: services.php?month=$month&year=$year");
        exit;
    } else {
        echo "<script>alert('Impossible d\'ajouter un service à une date passée.');</script>";
    }
}

$stmt = $pdo->prepare("SELECT * FROM services WHERE MONTH(service_date) = ? AND YEAR(service_date) = ?");
$stmt->execute([$month, $year]);
$services = $stmt->fetchAll();

$serviceMap = [];
foreach ($services as $s) {
    $day = (int) date('j', strtotime($s['service_date']));
    $serviceMap[$day][] = $s;
}

$prevMonth = $month == 1 ? 12 : $month - 1;
$prevYear = $month == 1 ? $year - 1 : $year;
$nextMonth = $month == 12 ? 1 : $month + 1;
$nextYear = $month == 12 ? $year + 1 : $year;
?>

<style>
    html, body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: #f6f8fa;
        color: #333;
    }
    header, footer {
        width: 100%;
    }
    main {
        padding: 20px;
    }
    h2, h3 {
        color: #2c3e50;
    }
    .container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        align-items: flex-start;
    }
    .calendar-wrapper {
        flex: 2;
        min-width: 600px;
    }
    .form-wrapper {
        flex: 1;
        min-width: 300px;
    }
    table.calendar {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-bottom: 30px;
    }
    table.calendar th, table.calendar td {
        border: 1px solid #ccc;
        vertical-align: top;
        padding: 8px;
        height: 140px;
        background: #fff;
    }
    table.calendar th {
        background: #e8edf1;
    }
    .service {
        background-color: #e0f0ff;
        margin-top: 6px;
        padding: 5px;
        border-radius: 5px;
        font-size: 0.85em;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .actions {
        margin-top: 5px;
        font-size: 0.8em;
    }
    form {
        background: #fff;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    form label {
        display: block;
        margin-top: 12px;
        font-weight: bold;
    }
    form input, form textarea {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    form input[type="submit"] {
        margin-top: 16px;
        background: #3498db;
        color: #fff;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }
    form input[type="submit"]:hover {
        background: #2980b9;
    }
</style>

<main>
    <h2>Calendrier des Services - <?= strftime('%B %Y', strtotime("$year-$month-01")) ?></h2>
    <p>
        <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>">&laquo; Mois précédent</a> |
        <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">Mois suivant &raquo;</a>
    </p>

    <div class="container">
        <div class="calendar-wrapper">
            <table class="calendar">
                <tr>
                    <?php foreach (['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'] as $dayName): ?>
                        <th><?= $dayName ?></th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php
                    for ($i = 0; $i < $firstDayOfWeek; $i++) echo '<td></td>';
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $weekday = ($firstDayOfWeek + $day - 1) % 7;
                        echo '<td>';
                        echo "<strong>$day</strong>";
                        if (!empty($serviceMap[$day])) {
                            foreach ($serviceMap[$day] as $s) {
                                echo "<div class='service'><strong>" . htmlspecialchars($s['title']) . "</strong><br>";
                                echo htmlspecialchars($s['service_time']) . "<br>";
                                echo htmlspecialchars($s['category']) . "<br>";
                                echo number_format($s['price'], 2) . " €";
                                echo "<div class='actions'><a href='?delete=" . $s['id'] . "' onclick='return confirm(\"Supprimer ce service ?\")'>Supprimer</a></div></div>";
                            }
                        }
                        echo '</td>';
                        if ($weekday == 6) echo '</tr><tr>';
                    }
                    ?>
                </tr>
            </table>
        </div>
        <div class="form-wrapper">
            <h3>Ajouter un service</h3>
            <form method="post">
                <label for="date">Date :</label>
                <input type="date" name="date" id="date" required>

                <label for="time">Heure :</label>
                <input type="time" name="time" id="time" required>

                <label for="title">Titre :</label>
                <input type="text" name="title" id="title" required>

                <label for="category">Catégorie :</label>
                <input type="text" name="category" id="category" required>

                <label for="description">Description :</label>
                <textarea name="description" id="description"></textarea>

                <label for="price">Prix :</label>
                <input type="number" name="price" id="price" step="0.01" required>

                <label for="capacity">Capacité :</label>
                <input type="number" name="capacity" id="capacity" required>

                <label for="duration">Durée (min) :</label>
                <input type="number" name="duration" id="duration" value="60" required>

                <input type="submit" value="Ajouter">
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>