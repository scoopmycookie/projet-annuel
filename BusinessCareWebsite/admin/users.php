<?php
require_once '../includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['archive_id'], $_POST['reason'])) {
        $id = (int) $_POST['archive_id'];
        $reason = trim($_POST['reason']);
        if ($id && $reason !== '') {
            $pdo->prepare("UPDATE users SET status = 'archived', archive_reason = ? WHERE id = ?")
                ->execute([$reason, $id]);
            header("Location: users.php?archived=1");
            exit;
        }
    }

    if (isset($_POST['unarchive_id'])) {
        $id = (int) $_POST['unarchive_id'];
        $pdo->prepare("UPDATE users SET status = 'approved', archive_reason = NULL WHERE id = ?")
            ->execute([$id]);
        header("Location: users.php?unarchived=1");
        exit;
    }
}

$users = $pdo->query("
    SELECT users.id, users.firstname, users.name, users.email, users.phone, users.position, users.role,
           users.status, users.archive_reason, companies.name AS company_name
    FROM users
    LEFT JOIN companies ON users.company_id = companies.id
    ORDER BY users.created_at DESC
")->fetchAll();
?>

<section class="form-section">
    <h2>Gestion des utilisateurs</h2>

    <?php if (isset($_GET['added'])): ?>
        <p style="color: green;">Nouvel utilisateur ajouté avec succès.</p>
    <?php elseif (isset($_GET['archived'])): ?>
        <p style="color: orange;">Utilisateur archivé.</p>
    <?php elseif (isset($_GET['unarchived'])): ?>
        <p style="color: green;">Utilisateur désarchivé.</p>
    <?php endif; ?>

    <p><a href="add_user.php" class="cta-button">Ajouter un utilisateur</a></p>

    <input type="text" id="searchInput" placeholder="Rechercher..." style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

    <?php if (empty($users)): ?>
        <p>Aucun utilisateur enregistré.</p>
    <?php else: ?>
        <table id="usersTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f2f2f2;">
                    <th style="padding: 10px; border: 1px solid #ccc;">Prénom</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Nom</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Email</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Téléphone</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Poste</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Rôle</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Statut</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Entreprise</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="user-row" style="<?= $user['status'] === 'archived' ? 'background:#f9f9f9; color:#999;' : '' ?>">
                        <td><?= htmlspecialchars($user['firstname']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= htmlspecialchars($user['position']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <?= htmlspecialchars($user['status']) ?>
                            <?php if ($user['status'] === 'archived' && $user['archive_reason']): ?>
                                <br><small>Motif : <?= htmlspecialchars($user['archive_reason']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['company_name'] ?? 'Aucune') ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $user['id'] ?>">Modifier</a>
                            <?php if ($user['status'] !== 'archived'): ?>
                                <form method="POST" style="display:inline-block; margin-top:5px;">
                                    <input type="hidden" name="archive_id" value="<?= $user['id'] ?>">
                                    <input type="text" name="reason" placeholder="Motif..." required style="width: 120px;">
                                    <button type="submit" style="border:none; background:none; color:#c00; cursor:pointer;">Archiver</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="display:inline-block; margin-top:5px;">
                                    <input type="hidden" name="unarchive_id" value="<?= $user['id'] ?>">
                                    <button type="submit" style="border:none; background:none; color:#28a745; cursor:pointer;">Désarchiver</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? 'table-row' : 'none';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
