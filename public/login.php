<?php
session_start();
require '../database/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirection si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, first_name, last_name, password, role, status FROM users WHERE email = ?");
    if (!$stmt) {
        die("Erreur SQL : " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Vérification du statut
        if ($user['status'] === 'archived') {
            $error = "Votre compte est archivé. Contactez l'administration.";
        } elseif ($user['status'] === 'banned') {
            $error = "Votre compte a été banni.";
        } elseif (password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role'];

            
            switch ($user['role']) {
                case 'admin':
                    header("Location: /admin/dashboard.php");
                    break;
                case 'employee':
                    header("Location: /employees/dashboard.php");
                    break;
                case 'client':
                    header("Location: /clients/dashboard.php");
                    break;
                default:
                    header("Location: /public/dashboard.php");
                    break;
            }
            
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Aucun utilisateur trouvé avec cet email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Business Care</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

<main>
    <section class="hero">
        <div class="container">
            <h1>Connexion</h1>
            <p>Accédez à votre espace personnel</p>
        </div>
    </section>

    <section class="login-form">
        <div class="container">
            <form action="login.php" method="POST">
                <?php if (isset($error)) : ?>
                    <p class="error-msg"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" class="btn">Se connecter</button>
            </form>
        </div>
    </section>
</main>

<?php include '../includes/footer_public.php'; ?>
</body>
</html>
