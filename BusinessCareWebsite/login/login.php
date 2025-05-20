<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';
require_once '../includes/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'approved'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {

    

        if (!isset($error)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['company_id'] = $user['company_id'];

            switch ($user['role']) {
                case 'admin':
                    header("Location: ../admin/dashboard.php");
                    break;
                case 'client':
                    header("Location: ../client/dashboard.php");
                    break;
                case 'employee':
                    header("Location: ../employee/dashboard.php");
                    break;
                case 'provider':
                    header("Location: ../provider/dashboard.php");
                    break;
            }
            exit;
        }
    } else {
        $error = $error ?? "Email ou mot de passe invalide, ou compte non approuvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Business Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
  html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
  }
  main {
    flex: 1;
  }
</style>
</head>
<body>

<h1>Connexion</h1>


<main class="form-section">
    <?php if (!empty($error)): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="quote-form">
        <input type="email" name="email" placeholder="Adresse email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</main>

</body>

</html>
<?require_once '../includes/footer.php';
