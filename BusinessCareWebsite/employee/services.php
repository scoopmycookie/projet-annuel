<?php
require_once '../includes/db.php';
include 'includes/header.php';

$employee_id = $_SESSION['user_id'];

if (isset($_GET['inscrire'])) {
    $service_id = (int)$_GET['inscrire'];
    $stmt = $pdo->prepare("INSERT IGNORE INTO service_registrations (user_id, service_id) VALUES (?, ?)");
    $stmt->execute([$employee_id, $service_id]);
    header("Location: services.php");
    exit;
}

if (isset($_GET['desinscrire'])) {
    $service_id = (int)$_GET['desinscrire'];
    $stmt = $pdo->prepare("DELETE FROM service_registrations WHERE user_id = ? AND service_id = ?");
    $stmt->execute([$employee_id, $service_id]);
    header("Location: services.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT s.*, 
        (SELECT COUNT(*) FROM service_registrations WHERE service_id = s.id) as inscrit_count,
        (SELECT COUNT(*) FROM service_registrations WHERE service_id = s.id AND user_id = ?) as est_inscrit,
        (SELECT COUNT(*) FROM service_reviews WHERE service_id = s.id AND user_id = ?) as deja_note,
        (SELECT AVG(rating) FROM service_reviews WHERE service_id = s.id) as moyenne_note
    FROM services s
    ORDER BY s.service_date ASC
");
$stmt->execute([$employee_id, $employee_id]);
$services = $stmt->fetchAll();
?>

<main class="form-section">
    <h2 style="margin-bottom: 20px;">Services disponibles</h2>

    <?php if (empty($services)): ?>
        <div style="background-color: #fff3cd; padding: 15px; border-left: 5px solid #ffc107; border-radius: 5px;">
            Aucun service enregistré pour le moment.
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background-color: #003566; color: white; text-align: left;">
                    <th style="padding: 12px;">Titre</th>
                    <th style="padding: 12px;">Date</th>
                    <th style="padding: 12px;">Heure</th>
                    <th style="padding: 12px;">Durée</th>
                    <th style="padding: 12px;">Place disponible</th>
                    <th style="padding: 12px;">Restantes</th>
                    <th style="padding: 12px;">Prix (€)</th>
                    <th style="padding: 12px;">Action</th>
                    <th style="padding: 12px;">Évaluation</th>
                    <th style="padding: 12px;">Avis</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $srv): ?>
                    <?php
                        $places_restantes = $srv['capacity'] - $srv['inscrit_count'];
                        $est_inscrit = $srv['est_inscrit'] > 0;
                        $deja_note = $srv['deja_note'] > 0;
                        $past = strtotime($srv['service_date']) < time();
                    ?>
                    <tr style="background-color: #fefefe; border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;"><?= htmlspecialchars($srv['title']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($srv['service_date']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($srv['service_time']) ?></td>
                        <td style="padding: 10px;"><?= $srv['duration'] ?> min</td>
                        <td style="padding: 10px;"><?= $srv['capacity'] ?></td>
                        <td style="padding: 10px;"><?= $places_restantes ?></td>
                        <td style="padding: 10px;"><?= number_format($srv['price'], 2) ?> €</td>
                        <td style="padding: 10px;">
                            <?php if ($est_inscrit): ?>
                                <a href="?desinscrire=<?= $srv['id'] ?>" class="cta-button" style="background-color: #dc3545; color: white;">Se désinscrire</a>
                            <?php elseif ($places_restantes > 0): ?>
                                <a href="?inscrire=<?= $srv['id'] ?>" class="cta-button" style="background-color: #198754; color: white;">S'inscrire</a>
                            <?php else: ?>
                                <span style="color: red;">Complet</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px;">
                            <?php if ($est_inscrit && $past && !$deja_note): ?>
                                <form action="submit_review.php" method="POST">
                                    <input type="hidden" name="service_id" value="<?= $srv['id'] ?>">
                                    <select name="rating" required>
                                        <option value="">Note</option>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                                        <?php endfor; ?>
                                    </select>
                                    <input type="text" name="comment" placeholder="Commentaire">
                                    <button type="submit">OK</button>
                                </form>
                            <?php elseif ($deja_note): ?>
                                <span style="color: green;">Évalué</span>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px; font-size: 0.85em;">
                            <?= $srv['moyenne_note'] ? round($srv['moyenne_note'], 1) . ' ⭐' : 'Pas encore noté' ?><br>
                            <?php
                            $rev = $pdo->prepare("SELECT r.rating, r.comment, u.firstname FROM service_reviews r JOIN users u ON r.user_id = u.id WHERE r.service_id = ?");
                            $rev->execute([$srv['id']]);
                            foreach ($rev as $r): ?>
                                <div><strong><?= htmlspecialchars($r['firstname']) ?></strong> : <?= str_repeat('⭐', $r['rating']) ?> - <em><?= htmlspecialchars($r['comment']) ?></em></div>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
