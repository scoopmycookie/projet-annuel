<?php
require_once '../includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_info'])) {
        $name = trim($_POST['name']);
        $firstname = trim($_POST['firstname']);
        $phone = trim($_POST['phone']);
        $position = trim($_POST['position']);

        $stmt = $pdo->prepare("UPDATE users SET name = ?, firstname = ?, phone = ?, position = ? WHERE id = ?");
        $stmt->execute([$name, $firstname, $phone, $position, $user_id]);
        $message = "✅ Vos informations ont été mises à jour.";
    }

    if (isset($_POST['update_password'])) {
        $old = $_POST['old_password'];
        $new1 = $_POST['new_password'];
        $new2 = $_POST['confirm_password'];

        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!password_verify($old, $user['password'])) {
            $error = "❌ L'ancien mot de passe est incorrect.";
        } elseif ($new1 !== $new2) {
            $error = "❌ Les nouveaux mots de passe ne correspondent pas.";
        } else {
            $newHash = password_hash($new1, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newHash, $user_id]);
            $message = "✅ Mot de passe mis à jour avec succès.";
        }
    }
}

$stmt = $pdo->prepare("SELECT name, firstname, email, phone, position, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<main class="form-section" style="max-width: 800px; margin: auto;">
    <h2>Mon Profil</h2>

    <?php if ($message): ?>
        <div style="background: #d4edda; color: #155724; padding: 12px; border-left: 5px solid #28a745; border-radius: 5px; margin-bottom: 20px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php elseif ($error): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-left: 5px solid #dc3545; border-radius: 5px; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="margin-bottom: 40px;">
        <h3>Informations personnelles</h3>
        <label>Nom :</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <label>Prénom :</label>
        <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
        <label>Email :</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        <label>Téléphone :</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
        <label>Poste :</label>
        <input type="text" name="position" value="<?= htmlspecialchars($user['position']) ?>">
        <button type="submit" name="update_info" class="cta-button" style="margin-top: 15px;">Mettre à jour</button>
    </form>

    <form method="POST">
        <h3>Changer le mot de passe</h3>
        <label>Ancien mot de passe :</label>
        <input type="password" name="old_password" required>
        <label>Nouveau mot de passe :</label>
        <input type="password" name="new_password" required>
        <label>Confirmer le nouveau mot de passe :</label>
        <input type="password" name="confirm_password" required>
        <button type="submit" name="update_password" class="cta-button" style="margin-top: 15px;">Changer le mot de passe</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
