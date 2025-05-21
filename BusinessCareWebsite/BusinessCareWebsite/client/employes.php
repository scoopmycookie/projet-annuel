<?php
require_once '../includes/db.php';
include 'includes/header.php';

$message = '';
$limit_reached = false;

$stmt = $pdo->prepare("SELECT employees FROM companies WHERE id = ?");
$stmt->execute([$_SESSION['company_id']]);
$company = $stmt->fetch();
$employee_limit = $company['employees'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM users WHERE company_id = ? AND role = 'employee' ORDER BY created_at DESC");
$stmt->execute([$_SESSION['company_id']]);
$employees = $stmt->fetchAll();
$current_count = count($employees);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($current_count >= $employee_limit) {
        $message = "Limite d'employés atteinte pour votre entreprise.";
        $limit_reached = true;
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (company_id, name, firstname, email, phone, password, role, position, status)
                               VALUES (?, ?, ?, ?, ?, ?, 'employee', ?, 'approved')");
        $stmt->execute([
            $_SESSION['company_id'],
            $_POST['name'],
            $_POST['firstname'],
            $_POST['email'],
            $_POST['phone'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['position']
        ]);
        $message = "Nouvel employé ajouté avec succès.";
        header("Location: employes.php");
        exit;
    }
}
?>

<main class="form-section">
    <h2>Gestion des Employés</h2>

    <?php if (!empty($message)): ?>
        <p style="color: <?= $limit_reached ? 'red' : 'green' ?>; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <p><strong>Employés déclarés :</strong> <?= $employee_limit ?> |
       <strong>Employés enregistrés :</strong> <?= $current_count ?></p>

    <?php if (!$limit_reached): ?>
    <form method="POST" style="margin-bottom: 30px;">
        <h3>Ajouter un employé</h3>
        <input type="text" name="name" placeholder="Nom" required style="width:100%;padding:10px;margin-bottom:10px;">
        <input type="text" name="firstname" placeholder="Prénom" style="width:100%;padding:10px;margin-bottom:10px;">
        <input type="email" name="email" placeholder="Email" required style="width:100%;padding:10px;margin-bottom:10px;">
        <input type="text" name="phone" placeholder="Téléphone" style="width:100%;padding:10px;margin-bottom:10px;">
        <input type="text" name="position" placeholder="Poste" style="width:100%;padding:10px;margin-bottom:10px;">
        <input type="password" name="password" placeholder="Mot de passe" required style="width:100%;padding:10px;margin-bottom:10px;">
        <button type="submit" class="cta-button">Ajouter</button>
    </form>
    <?php endif; ?>

    <h3>Liste des employés</h3>
    <input type="text" id="searchInput" placeholder="Rechercher..." style="width:100%;padding:10px;margin-bottom:20px;">

    <?php if (empty($employees)): ?>
        <p>Aucun employé enregistré.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #003566; color: white;">
                    <th style="padding: 10px;">Nom</th>
                    <th style="padding: 10px;">Prénom</th>
                    <th style="padding: 10px;">Email</th>
                    <th style="padding: 10px;">Téléphone</th>
                    <th style="padding: 10px;">Poste</th>
                    <th style="padding: 10px;">Statut</th>
                </tr>
            </thead>
            <tbody id="employeeTable">
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td style="padding: 10px;"><?= htmlspecialchars($emp['name']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($emp['firstname']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($emp['email']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($emp['phone']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($emp['position']) ?></td>
                        <td style="padding: 10px;">
                            <?= $emp['status'] === 'approved'
                                ? '<span style="color: green;">Actif</span>'
                                : '<span style="color: red;">En attente</span>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    let input = this.value.toLowerCase();
    let rows = document.querySelectorAll('#employeeTable tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(input) ? '' : 'none';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
