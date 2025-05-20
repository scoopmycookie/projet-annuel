
<?php
require_once '../includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

$stmt = $pdo->prepare("SELECT employees FROM companies WHERE id = ?");
$stmt->execute([$company_id]);
$company = $stmt->fetch();
$nb_employees = $company['employees'] ?? 0;

$question_limit = null;
$stmt = $pdo->query("SELECT * FROM pricing ORDER BY employee_min ASC");
$pricing = $stmt->fetchAll();
foreach ($pricing as $p) {
    if ($nb_employees >= $p['employee_min'] && (is_null($p['employee_max']) || $nb_employees <= $p['employee_max'])) {
        if ($p['pack'] === 'starter') $question_limit = 6;
        elseif ($p['pack'] === 'pro') $question_limit = 20;
        else $question_limit = null;
        break;
    }
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM chat_logs WHERE user_id = ?");
$stmt->execute([$user_id]);
$questions_used = (int)$stmt->fetchColumn();
$limit_reached = $question_limit !== null && $questions_used >= $question_limit;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$limit_reached) {
    $question = trim($_POST['question']);
    if (!empty($question)) {
        $stmt = $pdo->prepare("INSERT INTO chat_logs (user_id, question) VALUES (?, ?)");
        $stmt->execute([$user_id, $question]);

        // Auto-réponse simple
        $keywords = [
            'horaire' => 'Les horaires sont disponibles sur la page Services.',
            'paiement' => 'Vos factures sont visibles dans l’espace entreprise.',
            'inscription' => 'Vous pouvez vous inscrire à un service depuis la page Services.',
            'aide' => 'Pour toute aide, contactez l’admin via la messagerie.',
            'contact' => 'Vous pouvez écrire à l’administration via Messages.'
        ];
        $response = "Je n’ai pas compris votre question. Essayez un mot comme “horaire”, “paiement”, “aide”...";
        foreach ($keywords as $word => $reply) {
            if (stripos($question, $word) !== false) {
                $response = $reply;
                break;
            }
        }
        $stmt = $pdo->prepare("INSERT INTO chat_logs (user_id, question) VALUES (?, ?)");
        $stmt->execute([$user_id, '[BOT] ' . $response]);

        header("Location: chatbot.php");
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM chat_logs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>

<main class="form-section">
    <h2>Chatbot Employé</h2>
    <p><strong>Utilisation :</strong> <?= $questions_used ?><?php if ($question_limit !== null): ?> / <?= $question_limit ?><?php else: ?> (illimité)<?php endif; ?></p>

    <div style="margin-bottom: 20px;">
        <strong>Exemples de questions :</strong><br>
        <div style="margin-top: 10px;">
            <?php foreach (['Quels sont mes horaires ?', 'Comment payer ma facture ?', 'Comment m’inscrire à un service ?', 'J’ai besoin d’aide.', 'Comment contacter l’admin ?'] as $q): ?>
                <button onclick="document.getElementById('questionBox').value = '<?= $q ?>';" style="margin: 5px; padding: 8px 12px; border: none; background: #e0e0e0; border-radius: 20px; cursor: pointer;"><?= $q ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div style="background: #f9f9f9; padding: 15px; border-radius: 6px; max-height: 300px; overflow-y: auto;">
        <?php if (empty($history)): ?>
            <p>Aucune conversation pour l’instant.</p>
        <?php else: ?>
            <?php foreach ($history as $msg): ?>
                <div style="margin-bottom: 12px;">
                    <strong><?= str_starts_with($msg['question'], '[BOT]') ? 'Bot' : 'Vous' ?> :</strong><br>
                    <?= nl2br(htmlspecialchars(str_replace('[BOT] ', '', $msg['question']))) ?><br>
                    <small style="color: gray;"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($limit_reached): ?>
        <div style="margin-top: 20px; padding: 15px; background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; border-radius: 5px;">
            Vous avez atteint la limite de questions autorisées par votre abonnement.
        </div>
    <?php else: ?>
        <form method="POST" style="margin-top: 20px;">
            <textarea name="question" id="questionBox" rows="4" placeholder="Posez votre question..." style="width: 100%; padding: 10px;" required></textarea>
            <button type="submit" class="cta-button" style="margin-top: 10px;">Envoyer</button>
        </form>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
