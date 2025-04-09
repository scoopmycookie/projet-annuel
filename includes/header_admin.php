<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<header>
    <div class="container">
        <div class="logo">
            <a href="dashboard.php">
                <img src="../assets/images/logo.png" alt="Admin Dashboard">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Tableau de bord</a></li>
                <li><a href="manage_users.php">Utilisateurs</a></li>
                <li><a href="manage_companies.php">Entreprises</a></li>
                <li><a href="manage_providers.php">Fournisseurs</a></li>
                <li><a href="manage_services.php">Services</a></li>
                <li><a href="manage_quotes.php">Devis</a></li>
                <li><a href="manage_messages.php">Messages</a></li>
                <li><a href="../public/logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </div>
</header>