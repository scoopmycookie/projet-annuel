<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Récupération des devis
$query = "
    SELECT q.id, u.first_name, u.last_name, s.title AS service_title, q.message, q.status, q.created_at
    FROM quotes q
    JOIN users u ON q.user_id = u.id
    JOIN services s ON q.service_id = s.id
    ORDER BY q.created_at DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des devis</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📑 Gestion des devis</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($quote = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $quote['id'] ?></td>
                        <td><?= htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']) ?></td>
                        <td><?= htmlspecialchars($quote['service_title']) ?></td>
                        <td><?= nl2br(htmlspecialchars($quote['message'])) ?></td>
                        <td>
                            <?php if ($quote['status'] === 'en attente'): ?>
                                <span class="status-archived">En attente</span>
                            <?php elseif ($quote['status'] === 'accepté'): ?>
                                <span class="status-active">Accepté</span>
                            <?php else: ?>
                                <span class="status-banned">Refusé</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($quote['created_at'])) ?></td>
                        <td class="action-buttons">
                            <a href="accept_quote.php?id=<?= $quote['id'] ?>" class="btn btn-green">Accepter</a>
                            <a href="reject_quote.php?id=<?= $quote['id'] ?>" class="btn btn-red">Refuser</a>
                            <a href="delete_quote.php?id=<?= $quote['id'] ?>" class="btn btn-yellow" onclick="return confirm('Supprimer ce devis ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
