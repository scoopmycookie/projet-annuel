<?php
require_once '../includes/db.php';
include 'includes/header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$stmt = $pdo->prepare("SELECT * FROM invoices WHERE company_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['company_id']]);
$invoice = $stmt->fetch();

$must_pay = !$invoice || $invoice['status'] !== 'paid';
?>

<main class="form-section" style="text-align:center;">
    <h2>Bienvenue dans votre espace Prestataire</h2>
    <p style="margin-bottom: 30px;">Bonjour, <strong><?= htmlspecialchars($_SESSION['email']) ?></strong>.</p>

    <?php if ($must_pay): ?>
        <div style="background-color: #ffe8a1; border-left: 5px solid #ffc107; padding: 20px; border-radius: 6px; max-width: 600px; margin: auto;">
            <h3 style="margin-bottom: 10px; color: #856404;">Paiement requis</h3>
            <p>Votre entreprise n’a pas encore réglé sa dernière facture.</p>
            <p><strong>L’accès est temporairement restreint.</strong><br>Merci d’effectuer le paiement pour débloquer votre tableau de bord.</p>
        </div>
    <?php else: ?>
        <div style="background-color: #e3f9e5; border-left: 5px solid #28a745; padding: 20px; border-radius: 6px; max-width: 600px; margin: auto;">
            <h3 style="margin-bottom: 10px; color: #155724;">Accès autorisé</h3>
            <p>Votre accès est actif. Vous pouvez gérer vos services, consulter vos devis et répondre aux demandes des clients.</p>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
