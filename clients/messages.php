<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

$providers = $conn->query("SELECT id, name FROM providers");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinataire_id = $_POST['destinataire_id'];
    $destinataire_type = $_POST['destinataire_type'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];

    $nom = $first_name . ' ' . $last_name;
    $email = $_SESSION['email'];
    $sender_role = 'client';

    $stmt = $conn->prepare("INSERT INTO messages (nom, email, sujet, message, sender_role, destinataire_id, destinataire_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $nom, $email, $sujet, $message, $sender_role, $destinataire_id, $destinataire_type);
    $stmt->execute();
    $success = "Message envoyé avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie - Business Care</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
    <style>
        form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        textarea {
            width: 100%;
            height: 120px;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            margin-top: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<?php include '../includes/header_clients.php'; ?>

<main class="container">
    <h1>📨 Messagerie</h1>

    <?php if (isset($success)): ?>
        <p class="success-msg"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="destinataire_id">Destinataire</label>
        <select name="destinataire_id" id="destinataire_id" required onchange="updateDestinataireType(this)">
            <option value="0" data-type="admin">👨‍💼 Administrateur</option>
            <?php while ($prov = $providers->fetch_assoc()): ?>
                <option value="<?= $prov['id'] ?>" data-type="provider">📦 <?= htmlspecialchars($prov['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <input type="hidden" name="destinataire_type" id="destinataire_type" value="admin">

        <label for="sujet">Sujet</label>
        <input type="text" id="sujet" name="sujet" required>

        <label for="message">Message</label>
        <textarea id="message" name="message" required></textarea>

        <button type="submit" class="btn">Envoyer</button>
    </form>
</main>

<script>
function updateDestinataireType(select) {
    const selected = select.options[select.selectedIndex];
    const type = selected.getAttribute('data-type');
    document.getElementById('destinataire_type').value = type;
}
</script>

<?php include '../includes/footer_clients.php'; ?>
</body>
</html>
