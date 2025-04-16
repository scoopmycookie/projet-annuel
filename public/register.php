<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../database/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $company_name = $_POST['company_name'] ?? '';
    $siret_number = $_POST['siret_number'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $description = "Fournisseur inscrit"; 

    if (!$first_name || !$last_name || !$email || !$password || !$role) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }

    if ($role === 'user' && (!$company_name || !$siret_number)) {
        $error = "Veuillez renseigner le nom de l'entreprise et le numéro SIRET.";
    }

    if (!$error) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$check) die("Erreur préparation : " . $conn->error);
        $check->bind_param("s", $email);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users 
                (first_name, last_name, email, password, role, phone, address, company_name, siret_number, gender, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");

            if (!$stmt) die("Erreur SQL (users) : " . $conn->error);

            $stmt->bind_param(
                "ssssssssss",
                $first_name, $last_name, $email, $hashed_password,
                $role, $phone, $address, $company_name, $siret_number, $gender
            );

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['role'] = $role;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                if ($role === 'user') {
                    $company_stmt = $conn->prepare("INSERT INTO companies 
                        (name, address, email, phone, status, is_verified, is_subscribed) 
                        VALUES (?, ?, ?, ?, 'active', 0, 1)");

                    if (!$company_stmt) die("Erreur SQL (companies) : " . $conn->error);

                    $company_stmt->bind_param("ssss", $company_name, $address, $email, $phone);
                    $company_stmt->execute();
                }

                if ($role === 'supplier') {
                    $provider_stmt = $conn->prepare("INSERT INTO providers 
                        (name, email, phone, address, description, is_verified, gender) 
                        VALUES (?, ?, ?, ?, ?, 0, ?)");

                    if (!$provider_stmt) die("Erreur SQL (providers) : " . $conn->error);

                    $full_name = $first_name . ' ' . $last_name;
                    $provider_stmt->bind_param("ssssss", $full_name, $email, $phone, $address, $description, $gender);
                    $provider_stmt->execute();
                }

                header("Location: /public/waiting-for-validation.php");
                exit();
            } else {
                $error = "Erreur lors de l'inscription : " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/css/public.css">
    <script>
        function toggleCompanyFields(roleSelect) {
            const show = roleSelect.value === 'user';
            document.getElementById('company-fields').style.display = show ? 'block' : 'none';
            document.getElementById('company_name').required = show;
            document.getElementById('siret_number').required = show;
        }

        window.addEventListener('DOMContentLoaded', () => {
            const roleSelect = document.getElementById('role');
            toggleCompanyFields(roleSelect);
            roleSelect.addEventListener('change', () => toggleCompanyFields(roleSelect));
        });
    </script>
</head>
<body>
<?php include '../includes/header_public.php'; ?>

<main class="container">
    <h1>📝 Inscription</h1>

    <?php if ($error): ?>
        <div class="success-msg" style="background: #dc3545; color: white; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <section class="form-container">
        <form method="POST">
            <label for="first_name">Prénom</label>
            <input type="text" name="first_name" id="first_name" required>

            <label for="last_name">Nom</label>
            <input type="text" name="last_name" id="last_name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>

            <label for="phone">Téléphone</label>
            <input type="text" name="phone" id="phone">

            <label for="address">Adresse</label>
            <input type="text" name="address" id="address">

            <label for="gender">Genre</label>
            <select name="gender" id="gender">
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>

            <label for="role">Vous êtes :</label>
            <select name="role" id="role" required>
                <option value="">-- Choisir un rôle --</option>
                <option value="user">Client</option>
                <option value="supplier">Fournisseur</option>
            </select>

            <div id="company-fields" style="display: none;">
                <label for="company_name">Nom de l'entreprise</label>
                <input type="text" name="company_name" id="company_name">

                <label for="siret_number">Numéro SIRET</label>
                <input type="text" name="siret_number" id="siret_number">
            </div>

            <button type="submit" class="btn btn-green">S'inscrire</button>
        </form>
    </section>
</main>

<?php include '../includes/footer_public.php'; ?>
</body>
</html>
