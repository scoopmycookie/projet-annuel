<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, address, gender, role, company FROM users WHERE id = ?");
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
    <title>Mon Profil - Business Care</title>
    <link rel="stylesheet" href="../assets/css/public.css">
    <style>
        .profile-box {
            max-width: 700px;
            margin: 40px auto;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
        }

        .profile-box h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-info p {
            margin: 12px 0;
            font-size: 16px;
        }

        .btn-logout {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<?php include '../includes/header_public.php'; ?>

<main class="container">
    <div class="profile-box">
        <h1>👤 Mon Profil</h1>

        <div class="profile-info">
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($user['address']) ?></p>
            <p><strong>Genre :</strong> <?= htmlspecialchars($user['gender']) ?></p>
            <p><strong>Rôle :</strong> <?= ucfirst($user['role']) ?></p>
            <p><strong>Entreprise :</strong> <?= htmlspecialchars($user['company'] ?? 'Non renseignée') ?></p>
        </div>

        <a href="logout.php" class="btn-logout">🚪 Se déconnecter</a>
    </div>
</main>

<?php include '../includes/footer_public.php'; ?>

</body>
</html>
