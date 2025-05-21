<?php
require_once '../includes/db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM companies WHERE id = ?")->execute([$id]);
    header("Location: companies.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        $_POST['name'], $_POST['siret'], $_POST['industry'], $_POST['email'], $_POST['phone'],
        $_POST['website'], $_POST['address_street'], $_POST['address_city'], $_POST['address_postal_code'],
        $_POST['address_country'], $_POST['representative_name'], $_POST['employees']
    ];

    if (!empty($_POST['id'])) {
        $data[] = $_POST['id'];
        $pdo->prepare("UPDATE companies SET name=?, siret=?, industry=?, email=?, phone=?, website=?, address_street=?, address_city=?, address_postal_code=?, address_country=?, representative_name=?, employees=? WHERE id=?")->execute($data);
    } else {
        $pdo->prepare("INSERT INTO companies (name, siret, industry, email, phone, website, address_street, address_city, address_postal_code, address_country, representative_name, employees) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute($data);
    }
    header("Location: companies.php");
    exit;
}

$editCompany = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editCompany = $stmt->fetch();
}

$companies = $pdo->query("SELECT * FROM companies ORDER BY created_at DESC")->fetchAll();

include 'includes/header.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f9f9f9;
        margin: 20px;
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
    }
    form {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    form input, form button {
        display: block;
        width: 100%;
        margin: 8px 0;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }
    form button {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s;
    }
    form button:hover {
        background-color: #0056b3;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    table th {
        background-color: #f4f4f4;
    }
    table tr:hover {
        background-color: #f1f1f1;
    }
    a {
        color: #007bff;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
    details {
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 10px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    summary {
        font-weight: bold;
        cursor: pointer;
    }
</style>

<h2>Gestion des sociétés</h2>
<form method="POST" style="margin-bottom:40px; max-width: 600px;">
    <input type="hidden" name="id" value="<?= $editCompany['id'] ?? '' ?>">
    <input type="text" name="name" placeholder="Nom" value="<?= $editCompany['name'] ?? '' ?>" required>
    <input type="text" name="siret" placeholder="SIRET" value="<?= $editCompany['siret'] ?? '' ?>">
    <input type="text" name="industry" placeholder="Secteur" value="<?= $editCompany['industry'] ?? '' ?>">
    <input type="email" name="email" placeholder="Email" value="<?= $editCompany['email'] ?? '' ?>" required>
    <input type="text" name="phone" placeholder="Tél" value="<?= $editCompany['phone'] ?? '' ?>">
    <input type="text" name="website" placeholder="Site web" value="<?= $editCompany['website'] ?? '' ?>">
    <input type="text" name="address_street" placeholder="Rue" value="<?= $editCompany['address_street'] ?? '' ?>">
    <input type="text" name="address_city" placeholder="Ville" value="<?= $editCompany['address_city'] ?? '' ?>">
    <input type="text" name="address_postal_code" placeholder="Code postal" value="<?= $editCompany['address_postal_code'] ?? '' ?>">
    <input type="text" name="address_country" placeholder="Pays" value="<?= $editCompany['address_country'] ?? '' ?>">
    <input type="text" name="representative_name" placeholder="Représentant" value="<?= $editCompany['representative_name'] ?? '' ?>">
    <input type="number" name="employees" placeholder="Nb salariés" value="<?= $editCompany['employees'] ?? 1 ?>" min="1">
    <button type="submit"><?= $editCompany ? 'Mettre à jour' : 'Ajouter' ?> la société</button>
</form>

<?php foreach ($companies as $company): ?>
    <?php
    $stmt = $pdo->prepare("SELECT name, email, role, status FROM users WHERE company_id = ?");
    $stmt->execute([$company['id']]);
    $employees = $stmt->fetchAll();
    $count = count($employees);
    ?>
    <details>
        <summary><?= htmlspecialchars($company['name']) ?> (<?= $count ?> employé<?= $count > 1 ? 's' : '' ?>)</summary>
        <p><strong>Email :</strong> <?= htmlspecialchars($company['email']) ?></p>
        <p><strong>SIRET :</strong> <?= htmlspecialchars($company['siret']) ?></p>
        <p><strong>Nb salariés :</strong> <?= htmlspecialchars($company['employees']) ?></p>
        <p><a href="?edit=<?= $company['id'] ?>">Modifier</a> | <a href="?delete=<?= $company['id'] ?>" onclick="return confirm('Supprimer cette société ?')">Supprimer</a> | <a href="companies_contracts.php?company_id=<?= $company['id'] ?>">Contrats</a></p>

        <h4>Employés :</h4>
        <ul>
            <?php foreach ($employees as $emp): ?>
                <li><strong><?= htmlspecialchars($emp['name']) ?></strong> (<?= htmlspecialchars($emp['role']) ?>) - <?= htmlspecialchars($emp['email']) ?> [<?= htmlspecialchars($emp['status']) ?>]</li>
            <?php endforeach; ?>
        </ul>
    </details>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>