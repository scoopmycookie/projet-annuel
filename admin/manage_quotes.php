<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Traitement de la création
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company'];
    $plan = $_POST['plan'];

    $price_per_employee = match ($plan) {
        'starter' => 180,
        'basic' => 150,
        'premium' => 100,
        default => 0,
    };

    $stmt = $conn->prepare("INSERT INTO quotes (company, plan, price_per_employee, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isd", $company_id, $plan, $price_per_employee);
    $stmt->execute();
    header("Location: manage_quotes.php");
    exit();
}

// Données à afficher
$companies = $conn->query("SELECT id, name FROM companies ORDER BY name");
$quotes = $conn->query("
    SELECT q.*, c.name AS company_name 
    FROM quotes q 
    LEFT JOIN companies c ON q.company = c.id 
    ORDER BY q.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Devis</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .plan-options {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }
        .plan-option {
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            text-align: center;
            flex: 1;
            background-color: #222;
            color: white;
        }
        .plan-option:hover {
            border-color: orange;
        }
        input[type="radio"] {
            display: none;
        }
        input[type="radio"]:checked + .plan-option {
            border-color: #ff9800;
            background-color: #333;
        }
        .tarif-table {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }
        .tarif-table th, .tarif-table td {
            border: 1px solid #444;
            padding: 8px;
            text-align: center;
        }
        .tarif-table th {
            background-color: #ff9800;
            color: black;
        }
    </style>
</head>
<body>
<?php include '../includes/header_admin.php'; ?>

<main class="container">
    <h1>📄 Gestion des devis</h1>

    <section>
        <h2>➕ Créer un devis</h2>
        <form action="manage_quotes.php" method="POST">
            <label for="company">Entreprise :</label>
            <select name="company" required>
                <option value="">-- Sélectionner une entreprise --</option>
                <?php while ($company = $companies->fetch_assoc()): ?>
                    <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Choisir une formule :</label>
            <div class="plan-options">
                <label>
                    <input type="radio" name="plan" value="starter" required>
                    <div class="plan-option">Starter (180€/an)</div>
                </label>
                <label>
                    <input type="radio" name="plan" value="basic">
                    <div class="plan-option">Basic (150€/an)</div>
                </label>
                <label>
                    <input type="radio" name="plan" value="premium">
                    <div class="plan-option">Premium (100€/an)</div>
                </label>
            </div>

            <button type="submit" class="btn btn-orange">Créer le devis</button>
        </form>
    </section>

    <table class="tarif-table">
        <thead>
            <tr>
                <th>Offre</th>
                <th>Effectif</th>
                <th>Activités BC</th>
                <th>RDV médicaux</th>
                <th>Supp. Médicaux</th>
                <th>Chatbot</th>
                <th>Fiches BC</th>
                <th>Conseils</th>
                <th>Communautés</th>
                <th>Tarif</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Starter</td>
                <td>jusqu'à 30</td>
                <td>2</td>
                <td>1</td>
                <td>75€/rdv</td>
                <td>6 questions</td>
                <td>illimité</td>
                <td>non</td>
                <td>-</td>
                <td>180€</td>
            </tr>
            <tr>
                <td>Basic</td>
                <td>jusqu'à 250</td>
                <td>3</td>
                <td>2</td>
                <td>75€/rdv</td>
                <td>20 questions</td>
                <td>illimité</td>
                <td>oui</td>
                <td>accès illimité</td>
                <td>150€</td>
            </tr>
            <tr>
                <td>Premium</td>
                <td>251 et +</td>
                <td>4</td>
                <td>3</td>
                <td>50€/rdv</td>
                <td>illimité</td>
                <td>illimité</td>
                <td>oui personnalisés</td>
                <td>accès illimité</td>
                <td>100€</td>
            </tr>
        </tbody>
    </table>

    <h2>📋 Liste des devis</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Entreprise</th>
                    <th>Formule</th>
                    <th>Tarif/Employé</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($quote = $quotes->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($quote['company_name']) ?></td>
                        <td><?= ucfirst($quote['plan']) ?></td>
                        <td><?= number_format($quote['price_per_employee'], 2) ?> €</td>
                        <td><?= date('d/m/Y', strtotime($quote['created_at'])) ?></td>
                        <td class="action-buttons">
                            <a href="edit_quote.php?id=<?= $quote['id'] ?>" class="btn btn-yellow">Modifier</a>
                            <a href="delete_quote.php?id=<?= $quote['id'] ?>" class="btn btn-red" onclick="return confirm('Supprimer ce devis ?')">Supprimer</a>
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
