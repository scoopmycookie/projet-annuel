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
                L'accès aux fonctionnalités est temporairement restreint.
            </div>
        </main>";
    include 'includes/footer.php';
    exit;
}
?>

<main class="form-section">
    <h2>Bienvenue dans votre espace Client</h2>
    <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['email']) ?></strong></p>

    <div style="margin-top: 20px;">
        <p>Votre entreprise est à jour de ses paiements. Vous pouvez :</p>
        <ul>
            <li>📄 Voir vos factures</li>
            <li>👥 Gérer vos employés</li>
            <li>🛠 Accéder aux services Business Care</li>
        </ul>
    </div>
</main>

<?php include 'includes/footer.php'; ?>