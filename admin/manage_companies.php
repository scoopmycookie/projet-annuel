<?php
session_start();
require_once('../db/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// 📌 Ajouter une entreprise
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_company'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);

    $sql = "INSERT INTO companies (name, email, address, phone) VALUES (:name, :email, :address, :phone)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':address' => $address,
        ':phone' => $phone
    ]);

    header('Location: manage_companies.php');
    exit();
}

// 📌 Supprimer une entreprise
if (isset($_GET['delete'])) {
    $companyId = $_GET['delete'];
    $sql = "DELETE FROM companies WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $companyId]);

    header('Location: manage_companies.php');
    exit();
}

// 📌 Ajouter une transaction (Paiement, Contrat, Devis)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_transaction'])) {
    $company_id = $_POST['company_id'];
    $type = $_POST['type'];
    $description = htmlspecialchars($_POST['description']);
    $amount = $_POST['amount'];
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : NULL;

    $sql = "INSERT INTO transactions (company_id, type, description, amount, due_date) 
            VALUES (:company_id, :type, :description, :amount, :due_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':company_id' => $company_id,
        ':type' => $type,
        ':description' => $description,
        ':amount' => $amount,
        ':due_date' => $due_date
    ]);

    header('Location: manage_companies.php');
    exit();
}

// 📌 Supprimer une transaction
if (isset($_GET['delete_transaction'])) {
    $transactionId = $_GET['delete_transaction'];
    $sql = "DELETE FROM transactions WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $transactionId]);

    header('Location: manage_companies.php');
    exit();
}

// 📌 Récupération des entreprises et transactions
$companies = $pdo->query("SELECT * FROM companies")->fetchAll(PDO::FETCH_ASSOC);
$transactions = $pdo->query("
    SELECT transactions.*, companies.name 
    FROM transactions 
    JOIN companies ON transactions.company_id = companies.id 
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="main-content">
    <h1>Gestion des Entreprises</h1>

    <!-- 🔹 Formulaire d'ajout d'entreprise -->
    <div class="form-container">
        <h2>Ajouter une entreprise</h2>
        <form action="manage_companies.php" method="POST">
            <label>Nom</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Adresse</label>
            <input type="text" name="address" required>

            <label>Téléphone</label>
            <input type="text" name="phone" required>

            <button type="submit" name="add_company" class="btn">Ajouter</button>
        </form>
    </div>

    <!-- 🔹 Liste des entreprises -->
    <h2>Liste des Entreprises</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td><?= $company['id']; ?></td>
                        <td><?= htmlspecialchars($company['name']); ?></td>
                        <td><?= htmlspecialchars($company['email']); ?></td>
                        <td><?= htmlspecialchars($company['address']); ?></td>
                        <td><?= htmlspecialchars($company['phone']); ?></td>
                        <td>
                            <a href="edit_company.php?id=<?= $company['id']; ?>" class="btn-edit">Modifier</a>
                            <a href="manage_companies.php?delete=<?= $company['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer cette entreprise ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 🔹 Formulaire d'ajout de transaction -->
    <h2>Ajouter une Transaction</h2>
    <form action="manage_companies.php" method="POST">
        <label>Entreprise</label>
        <select name="company_id" required>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id']; ?>"><?= htmlspecialchars($company['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label>Type de Transaction</label>
        <select name="type" required>
            <option value="Paiement">Paiement</option>
            <option value="Contrat">Contrat</option>
            <option value="Devis">Devis</option>
        </select>

        <label>Description</label>
        <input type="text" name="description" required>

        <label>Montant (€)</label>
        <input type="number" name="amount" step="0.01" required>

        <label>Date d'échéance (Facultatif)</label>
        <input type="date" name="due_date">

        <button type="submit" name="add_transaction" class="btn">Ajouter</button>
    </form>

    <!-- 🔹 Liste des transactions -->
    <h2>Transactions (Paiements, Contrats, Devis)</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Entreprise</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Montant (€)</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction['id']; ?></td>
                        <td><?= htmlspecialchars($transaction['name']); ?></td>
                        <td><?= htmlspecialchars($transaction['type']); ?></td>
                        <td><?= htmlspecialchars($transaction['description']); ?></td>
                        <td><?= number_format($transaction['amount'], 2); ?> €</td>
                        <td><?= ucfirst($transaction['status']); ?></td>
                        <td><a href="manage_companies.php?delete_transaction=<?= $transaction['id']; ?>" class="btn-delete">Supprimer</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
