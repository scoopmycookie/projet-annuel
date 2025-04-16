<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplier') {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT title, start_date, end_date FROM services WHERE company = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => $row['title'],
        'start' => $row['start_date'],
        'end' => date('Y-m-d', strtotime($row['end_date'] . ' +1 day')),
    ];
}

if (empty($events)) {
    $events[] = [
        'title' => 'Aucun service trouvé',
        'start' => date('Y-m-d'),
        'end' => date('Y-m-d', strtotime('+1 day')),
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>📅 Calendrier des Services</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
    <link rel="stylesheet" href="../assets/css/providers.css">
    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<?php include '../includes/header_providers.php'; ?>

<main class="container">
    <h1 style="text-align:center">📅 Calendrier de vos services</h1>
    <div id='calendar'></div>
</main>

<?php include '../includes/footer_providers.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?= json_encode($events) ?>,
        eventColor: '#ff9800',
        eventTextColor: '#000'
    });
    calendar.render();
});
</script>
</body>
</html>
