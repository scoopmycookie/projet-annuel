<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../database/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$conn) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT id, first_name, last_name, password, role FROM users WHERE email = ?");
    if (!$stmt) {
        die("Erreur SQL : " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // DEBUG : Vérifier les mots de passe
        echo "Mot de passe saisi : " . $password . "<br>";
        echo "Mot de passe hashé en base : " . $user['password'] . "<br>";

        if (password_verify($password, $user['password'])) {
            echo "Mot de passe correct !";
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role'];

            switch ($user['role']) {
                case 'admin':
                    header("Location: /business-care/admin/dashboard.php");
                    break;
                case 'employee':
                    header("Location: /business-care/employees/dashboard.php");
                    break;
                case 'supplier':
                    header("Location: /business-care/suppliers/dashboard.php");
                    break;
                case 'client':
                default:
                    header("Location: /business-care/public/dashboard.php");
                    break;
            }
            exit();
        } else {
            echo "Mot de passe incorrect !";
            exit();
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Connexion</h1>
                <p>Accédez à votre espace personnel.</p>
            </div>
        </section>

        <section class="login-form">
            <div class="container">
                <form action="login.php" method="POST">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit" class="btn">Se connecter</button>
                </form>
            </div>
        </section>
    </main>

    <?php include '../includes/footer_public.php'; ?>
</body>
</html>
