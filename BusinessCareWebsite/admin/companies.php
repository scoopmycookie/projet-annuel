<?php
require_once '../includes/db.php';
include 'includes/header.php';

if (isset($_GET['archive_user'])) {
    $userId = (int) $_GET['archive_user'];
    $pdo->prepare("UPDATE users SET status = 'archived' WHERE id = ?")->execute([$userId]);
    header("Location: companies.php");
    exit;
}

$companies = $pdo->query("SELECT * FROM companies ORDER BY name ASC")->fetchAll();
?>

<section class="form-section">
    <h2>Liste des entreprises</h2>

    <?php if (empty($companies)): ?>
        <p>Aucune entreprise enregistrée.</p>
    <?php else: ?>
        <input type="text" id="employeeSearch" placeholder="Rechercher un employé..." style="width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:5px;">

        <div id="companyList">
            <?php foreach ($companies as $company): ?>
                <?php
                    $stmt = $pdo->prepare("SELECT id, name, email, role, status FROM users WHERE company_id = ?");
                    $stmt->execute([$company['id']]);
                    $employees = $stmt->fetchAll();
                    $employee_count = count($employees);
                ?>
                <details class="company-block" style="margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">
                    <summary class="company-summary" style="cursor:pointer; font-weight:bold;">
                        <?= htmlspecialchars($company['name']) ?> (SIRET : <?= htmlspecialchars($company['siret']) ?>)
                        — <?= $employee_count ?> employé<?= $employee_count > 1 ? 's' : '' ?>
                    </summary>
                    <div style="margin-left: 20px; margin-top: 10px;">
                        <p><strong>Email :</strong> <?= htmlspecialchars($company['email']) ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($company['phone']) ?></p>
                        <p><strong>Adresse :</strong> <?= htmlspecialchars($company['address_street']) ?>, <?= htmlspecialchars($company['address_zip']) ?> <?= htmlspecialchars($company['address_city']) ?></p>
                        <p><strong>Effectif déclaré :</strong> <?= htmlspecialchars($company['employees']) ?> salarié<?= $company['employees'] > 1 ? 's' : '' ?></p>
                        <p><strong>Créée le :</strong> <?= htmlspecialchars($company['created_at'] ?? 'N/A') ?></p>

                        <h4>Employés :</h4>
                        <?php
                        $grouped = ['client' => [], 'employee' => [], 'provider' => [], 'other' => []];
                        foreach ($employees as $emp) {
                            $role = in_array($emp['role'], ['client', 'employee', 'provider']) ? $emp['role'] : 'other';
                            $grouped[$role][] = $emp;
                        }

                        $roleLabels = ['client' => 'Clients', 'employee' => 'Employés', 'provider' => 'Prestataires', 'other' => 'Autres'];
                        $roleColors = ['client' => '#e3f2fd', 'employee' => '#e8f5e9', 'provider' => '#fff3e0', 'other' => '#f3e5f5'];
                        ?>

                        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 10px;">
                            <?php foreach ($grouped as $role => $group): ?>
                                <?php if (!empty($group)): ?>
                                    <div style="background: <?= $roleColors[$role] ?>; padding: 15px; border-radius: 8px; flex: 1 1 300px;">
                                        <h5 style="margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
                                            <?= $roleLabels[$role] ?>
                                        </h5>
                                        <ul style="list-style-type: none; padding-left: 0;">
                                            <?php foreach ($group as $emp): ?>
                                                <li style="margin-bottom: 8px;">
                                                    <strong><?= htmlspecialchars($emp['name']) ?></strong>
                                                    (<a href="mailto:<?= htmlspecialchars($emp['email']) ?>"><?= htmlspecialchars($emp['email']) ?></a>)
                                                    — <span style="font-size: 0.9em; color: <?= $emp['status'] === 'archived' ? '#999' : '#28a745' ?>;">
                                                        <?= htmlspecialchars($emp['status']) ?>
                                                    </span>
                                                    <?php if ($emp['status'] !== 'archived'): ?>
                                                        | <a href="?archive_user=<?= $emp['id'] ?>" onclick="return confirm('Archiver cet employé ?');" style="color: #c00;">Archiver</a>
                                                    <?php else: ?>
                                                        | <span style="color: #999;">Archivé</span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
document.getElementById('employeeSearch').addEventListener('keyup', function () {
    const query = this.value.toLowerCase();
    const blocks = document.querySelectorAll('.company-block');

    blocks.forEach(block => {
        const items = block.querySelectorAll('li');
        let match = false;

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            const isVisible = text.includes(query);
            item.style.display = isVisible ? 'list-item' : 'none';
            if (isVisible) match = true;
        });

        block.style.display = match ? 'block' : 'none';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
