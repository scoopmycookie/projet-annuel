<?php
session_start();
require '../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $company = $_POST['company'];
    $gender = $_POST['gender'];

    // Vérification si l'email existe déjà
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // Insertion dans la base de données
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, phone, address, company, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $first_name, $last_name, $email, $password, $role, $phone, $address, $company, $gender);
        
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['role'] = $role;
            switch ($role) {
                case 'admin':
                    header("Location: /business-care/admin/dashboard.php");
                    break;
                case 'employee':
                    header("Location: /business-care/employees/dashboard.php");
                    break;
                case 'supplier':
                    header("Location: /business-care/providers/dashboard.php");
                    break;
                case 'client':
                default:
                    header("Location: /business-care/public/dashboard.php");
                    break;
            }
            exit();
        } else {
            $error = "Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Inscription</h1>
                <p>Créez un compte pour accéder à votre espace personnel.</p>
            </div>
        </section>

        <section class="register-form">
            <div class="container">
                <form action="register.php" method="POST">
                    <?php if (isset($error)) : ?>
                        <p class="error-msg"> <?php echo $error; ?> </p>
                    <?php endif; ?>
                    <label for="first_name">Prénom</label>
                    <input type="text" id="first_name" name="first_name" required>

                    <label for="last_name">Nom</label>
                    <input type="text" id="last_name" name="last_name" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>

                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" required>

                    <label for="address">Adresse</label>
                    <input type="text" id="address" name="address" required>

                    <label for="company">Entreprise (si applicable)</label>
                    <input type="text" id="company" name="company">

                    <label for="gender">Genre</label>
                    <select id="gender" name="gender" required>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                    </select>

                    <label for="role">Choisir un rôle</label>
                    <select id="role" name="role" required>
                        <option value="client">Client</option>
                        <option value="employee">Employé</option>
                        <option value="supplier">Fournisseur</option>
                        <option value="admin">Administrateur</option>
                    </select>

                    <button type="submit" class="btn">S'inscrire</button>
                </form>
            </div>
        </section>
    </main>

    <?php include '../includes/footer_public.php'; ?>
</body>
</html>
