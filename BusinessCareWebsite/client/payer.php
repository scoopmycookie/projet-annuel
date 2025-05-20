<?php
require_once '../includes/db.php';
include 'includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM invoices WHERE company_id = ? AND status != 'paid' ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['company_id']]);
$invoice = $stmt->fetch();
?>

<main class="form-section">
    <h2>Paiement de votre facture</h2>

    <?php if (!$invoice): ?>
        <p style="background-color: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;">
            Vous n'avez aucune facture impayée pour le moment.
        </p>
    <?php else: ?>
        <p>Facture n° <strong>#<?= $invoice['id'] ?></strong> | Montant à régler : <strong><?= number_format($invoice['amount'], 2) ?> €</strong></p>

        <p>Après validation du paiement :</p>
        <ul style="margin-bottom: 20px;">
            <li>✔ Votre facture sera marquée comme payée</li>
            <li>✔ Votre entreprise aura accès aux services Business Care</li>
            <li>✔ Un reçu PDF sera automatiquement ajouté à votre historique</li>
        </ul>

        <div id="paypal-container-LYKUZSYLAXZ46"></div>

        <script src="https://www.paypal.com/sdk/js?client-id=BAA8yKyeGIqZSF3pPQh_uq2S9xNS-ALeiQMq01xds7qF5DLhdk1vEwPrcIy0NaZQT9Zdj1zP_FkBYIHlsQ&components=hosted-buttons&disable-funding=venmo&currency=EUR"></script>
        <script>
            paypal.HostedButtons({
                hostedButtonId: "LYKUZSYLAXZ46",
            }).render("#paypal-container-LYKUZSYLAXZ46");
        </script>

        <p style="margin-top: 40px;">
            🔎 <a href="factures.php"><strong>Voir toutes mes factures</strong></a>
        </p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
