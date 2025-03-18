<?php
session_start();
require_once('../db/config.php');

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

// 📌 Récupérer le nombre d'utilisateurs par rôle
$queryUsers = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
$stmtUsers = $pdo->query($queryUsers);
$roleCounts = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

// 📌 Récupérer le nombre total d'activités
$queryActivities = "SELECT COUNT(*) as count FROM activities";
$stmtActivities = $pdo->query($queryActivities);
$activitiesCount = $stmtActivities->fetch(PDO::FETCH_ASSOC);

// Initialisation des compteurs
$admins = $employees = $companies = 0;
$activities = $activitiesCount['count'];

foreach ($roleCounts as $row) {
    switch ($row['role']) {
        case 'admin':
            $admins = $row['count'];
            break;
        case 'employe':
            $employees = $row['count'];
            break;
        case 'prestataire':
            $companies = $row['count'];
            break;
    }
}

include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="main-content">
    <h1>Tableau de bord Admin</h1>

    <div class="dashboard-cards">
        <div class="card">
            <i class="fas fa-user-shield"></i>
            <h3><?php echo $admins; ?></h3>
            <p>Admins</p>
        </div>
        <div class="card">
            <i class="fas fa-users"></i>
            <h3><?php echo $employees; ?></h3>
            <p>Employés</p>
        </div>
        <div class="card">
            <i class="fas fa-building"></i>
            <h3><?php echo $companies; ?></h3>
            <p>Prestataires</p>
        </div>
        <div class="card">
            <i class="fas fa-calendar-alt"></i>
            <h3><?php echo $activities; ?></h3>
            <p>Activités</p>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
