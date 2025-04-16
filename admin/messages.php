<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$query = "
    SELECT u.id, u.first_name, u.last_name, u.role
    FROM users u
    WHERE u.id IN (
        SELECT DISTINCT destinataire_id FROM messages WHERE destinataire_type = 'admin'
        UNION
        SELECT DISTINCT id FROM users WHERE id IN (
            SELECT destinataire_id FROM messages WHERE sender_role = 'admin'
        )
    )
    ORDER BY u.last_name
";

$users = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📨 Conversations</h1>
    <ul>
        <?php while ($user = $users->fetch_assoc()): ?>
            <li>
                <a href="conversation.php?id=<?= $user['id'] ?>">
                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?> (<?= $user['role'] ?>)
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</main>

<?php include '../includes/footer_admin.php'; ?>
</body>
</html>
