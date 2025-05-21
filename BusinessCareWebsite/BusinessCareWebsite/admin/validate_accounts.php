<?php
require_once '../includes/db.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
            $stmt = $pdo->prepare("SELECT id FROM companies WHERE siret = ?");
            $stmt->execute([$request['siret']]);
            $existingCompany = $stmt->fetch();

            if ($existingCompany) {
                $company_id = $existingCompany['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO companies (name, siret, email, phone, website, address_street, address_city, address_postal_code, address_country, representative_name)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $request['company_name'], $request['siret'], $request['email'], $request['phone'], $request['website'],
                    $request['address_street'], $request['address_city'], $request['address_postal_code'],
                    $request['address_country'], $request['representative_name']
                ]);
                $company_id = $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$request['email']]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                header("Location: validate_accounts.php?error=email-exists");
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO users (company_id, name, email, password, role, status)
                                   VALUES (?, ?, ?, ?, ?, 'approved')");
            $stmt->execute([
                $company_id, $request['representative_name'], $request['email'],
                $request['password'], $request['role']
            ]);

            $stmt = $pdo->prepare("SELECT employees FROM companies WHERE id = ?");
            $stmt->execute([$company_id]);
            $company = $stmt->fetch();
            $employee_count = $company['employees'] ?? 0;

            $tarif = 0;
            $stmt = $pdo->query("SELECT * FROM pricing ORDER BY employee_min ASC");
            $pricing = $stmt->fetchAll();

            foreach ($pricing as $row) {
                $min = (int)$row['employee_min'];
                $max = isset($row['employee_max']) ? (int)$row['employee_max'] : null;
                if (($employee_count >= $min) && (is_null($max) || $employee_count <= $max)) {
                    $tarif = (int)$row['tarif'];
                    break;
                }
            }

            $montant_total = $tarif * $employee_count;

            $stmt = $pdo->prepare("INSERT INTO invoices (company_id, amount, status, created_at) VALUES (?, ?, 'unpaid', NOW())");
            $stmt->execute([$company_id, $montant_total]);
        }

        $pdo->prepare("DELETE FROM registration_requests WHERE id = ?")->execute([$id]);
        header("Location: validate_accounts.php");
        exit;
    }
}

$requests = $pdo->query("SELECT * FROM registration_requests ORDER BY created_at DESC")->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<section class="form-section">
    <h2>Demandes d'inscription</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'email-exists'): ?>
        <p style="color:red; text-align:center;">Erreur : un utilisateur avec cet email existe déjà.</p>
    <?php endif; ?>

    <?php if (empty($requests)): ?>
        <p>Aucune demande en attente.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Entreprise</th>
                    <th>SIRET</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?= htmlspecialchars($req['company_name']) ?></td>
                        <td><?= htmlspecialchars($req['siret']) ?></td>
                        <td><?= htmlspecialchars($req['email']) ?></td>
                        <td><?= htmlspecialchars($req['role']) ?></td>
                        <td><?= $req['created_at'] ?></td>
                        <td>
                            <a href="?action=accept&id=<?= $req['id'] ?>">Accepter</a> |
                            <a href="?action=refuse&id=<?= $req['id'] ?>" onclick="return confirm('Refuser cette demande ?');">Refuser</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
