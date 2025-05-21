<?php include 'includes/header.php'; ?>

<section class="form-section">
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['email']) ?> !</p>

    <ul class="service-list">
        <li>
            <h4>📥 Inscriptions à valider</h4>
            <p>Consultez les demandes d'inscription client ou prestataire.</p>
            <a href="validate_accounts.php" class="cta-button">Valider les comptes</a>
        </li>
        <li>
            <h4>📨 Messages & Devis</h4>
            <p>Accédez aux messages reçus via le formulaire public.</p>
            <a href="messages.php" class="cta-button">Voir les messages</a>
        </li>
    </ul>
</section>
<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    $stmt = $pdo->prepare("SELECT * FROM registration_requests WHERE id = ?");
    $stmt->execute([$id]);
    $request = $stmt->fetch();

    if ($request) {
        if ($action === 'accept') {
            $stmt = $pdo->prepare("INSERT INTO companies (name, siret, email, phone, website, address_street, address_city, address_postal_code, address_country, representative_name)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $request['company_name'], $request['siret'], $request['email'], $request['phone'], $request['website'],
                $request['address_street'], $request['address_city'], $request['address_postal_code'],
                $request['address_country'], $request['representative_name']
            ]);
            $company_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO users (company_id, name, email, password, role, status)
                                   VALUES (?, ?, ?, ?, ?, 'approved')");
            $stmt->execute([
                $company_id, $request['representative_name'], $request['email'],
                $request['password'], $request['role']
            ]);

            $pdo->prepare("DELETE FROM registration_requests WHERE id = ?")->execute([$id]);

        } elseif ($action === 'reject') {
            $pdo->prepare("DELETE FROM registration_requests WHERE id = ?")->execute([$id]);
        }
    }
    header("Location: validate_accounts.php");
    exit;
}

$requests = $pdo->query("SELECT * FROM registration_requests ORDER BY created_at DESC")->fetchAll();
?>

<main class="form-section">
    <h2>Demandes d'inscription</h2>
    <?php if (empty($requests)): ?>
        <p>Aucune demande en attente.</p>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#003566; color:white;">
                    <th>Entreprise</th>
                    <th>SIRET</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $req): ?>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <td><?= htmlspecialchars($req['company_name']) ?></td>
                        <td><?= htmlspecialchars($req['siret']) ?></td>
                        <td><?= htmlspecialchars($req['email']) ?></td>
                        <td><?= htmlspecialchars($req['role']) ?></td>
                        <td><?= $req['created_at'] ?></td>
                        <td>
                            <a href="?action=accept&id=<?= $req['id'] ?>" style="color:green;">✅</a>
                            <a href="?action=reject&id=<?= $req['id'] ?>" style="color:red;" onclick="return confirm('Refuser cette demande ?');">❌</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
