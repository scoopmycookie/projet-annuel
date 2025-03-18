<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est un employé
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "employe") {
    header("Location: ../login.php"); // Redirection si non autorisé
    exit();
}

include('../includes/header.php');
?>

<style>
    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }
    .container h2 {
        color: #2C3E50;
    }
    .container ul {
        list-style: none;
        padding: 0;
    }
    .container ul li {
        background: #2C3E50;
        color: white;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        transition: 0.3s;
    }
    .container ul li:hover {
        background: #1A252F;
    }
    .btn-logout {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background: #E74C3C;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: 0.3s;
    }
    .btn-logout:hover {
        background: #C0392B;
    }
</style>

<div class="container">
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION["name"] ?? 'Employé'); ?> !</h2>
    <p>Voici votre tableau de bord.</p>

    <ul>
        <li><a href="services.php">Voir les services disponibles</a></li>
        <li><a href="planning.php">Consulter votre planning</a></li>
        <li><a href="profile.php">Modifier votre profil</a></li>
    </ul>

    <a href="../auth/logout.php" class="btn-logout">Se déconnecter</a>
</div>

<?php include('../includes/footer.php'); ?>
