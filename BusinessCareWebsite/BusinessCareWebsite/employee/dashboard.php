<?php
require_once '../includes/db.php';
include 'includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM invoices WHERE company_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['company_id']]);
$invoice = $stmt->fetch();

if (!$invoice || $invoice['status'] !== 'paid') {
    echo "<main class='form-section'>
            <div style='background-color: #ffeeba; padding: 20px; border-radius: 6px; color: #856404; text-align:center;'>
                Votre entreprise n’a pas encore réglé sa dernière facture.<br>
                L'accès est temporairement restreint.
            </div>
        </main>";
    include 'includes/footer.php';
    exit;
}
if ($_SESSION['role'] === 'employee') {
    $stmt = $pdo->prepare("SELECT first_login FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $firstLogin = $stmt->fetchColumn();

    if ($firstLogin) {
        echo '<script src="../assets/js/tutorial.js"></script>';
    }
}

?>


<main class="form-section">
    <h2>Bienvenue dans votre espace Employé</h2>
    <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['email']) ?></strong></p>

    <div style="margin-top: 20px;">
        <p>Vous avez accès aux services de l'entreprise via Business Care.</p>
        <ul>
            <li>📅 Voir le planning de vos prestations</li>
            <li>💬 Contacter un prestataire</li>
            <li>🔒 Gérer vos informations personnelles</li>
        </ul>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
