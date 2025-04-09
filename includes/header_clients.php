<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <div class="container">
        <div class="logo">
            <a href="/clients/dashboard.php">
                <img src="/assets/images/logo.png" alt="Business Care" style="height: 60px;">
            </a>
        </div>

        <nav>
            <ul>
                <li><a href="/clients/dashboard.php">🏠 Accueil</a></li>
                <li><a href="/clients/manage_quotes.php">📄 Devis</a></li>
                <li><a href="/clients/manage_invoices.php">💳 Factures</a></li>
                <li><a href="/clients/view_services.php">🛠 Services</a></li>
                <li><a href="/clients/manage_collaborators.php">👥 Collaborateurs</a></li>
                <li><a href="/clients/messages.php">📨 Messages</a></li>
                <li><a href="/public/logout.php" class="btn btn-red">Déconnexion</a></li>
            </ul>
        </nav>
    </div>
</header>
