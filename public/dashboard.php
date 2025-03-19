<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$stmt = $conn->prepare("SELECT first_name, last_name, email, role, phone, address, company, gender FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Erreur : Utilisateur introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header_public.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Bienvenue, <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>!</h1>
                <p>Rôle : <?php echo ucfirst($user['role']); ?></p>
            </div>
        </section>

        <section class="dashboard-content">
            <div class="container">
                <h2>Informations du Compte</h2>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                <p><strong>Adresse :</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <p><strong>Entreprise :</strong> <?php echo htmlspecialchars($user['company'] ? $user['company'] : 'Non renseigné'); ?></p>
                <p><strong>Genre :</strong> <?php echo htmlspecialchars($user['gender']); ?></p>

                <a href="logout.php" class="btn">Se Déconnecter</a>
            </div>
        </section>
    </main>

    <?php include '../includes/footer_public.php'; ?>
</body>
</html>
