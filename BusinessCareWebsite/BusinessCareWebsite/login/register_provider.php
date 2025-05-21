<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'];
    $siret = $_POST['siret'];
    $representative_name = $_POST['representative_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $website = $_POST['website'];
    $street = $_POST['address_street'];
    $city = $_POST['address_city'];
    $postal_code = $_POST['address_postal_code'];
    $country = $_POST['address_country'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare("INSERT INTO registration_requests (
        company_name, siret, representative_name, email, password, phone, website,
        address_street, address_city, address_postal_code, address_country, role, message
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'provider', ?)");
    
    $stmt->execute([
        $company_name, $siret, $representative_name, $email, $password, $phone, $website,
        $street, $city, $postal_code, $country, $message
    ]);

    header("Location: ../index.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Prestataire - Business Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <h1>Inscription Prestataire</h1>
</header>

<main>
<section class="form-section">
    <form method="POST" class="quote-form">
        <input type="text" name="company_name" placeholder="Nom de l'entreprise" required>
        <input type="text" name="siret" placeholder="Numéro SIRET" required>
        <input type="text" name="representative_name" placeholder="Nom du représentant" required>
        <input type="email" name="email" placeholder="Email professionnel" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="text" name="phone" placeholder="Téléphone">
        <input type="text" name="website" placeholder="Site web">
        <input type="text" name="address_street" placeholder="Adresse">
        <input type="text" name="address_city" placeholder="Ville">
        <input type="text" name="address_postal_code" placeholder="Code postal">
        <input type="text" name="address_country" placeholder="Pays">
        <textarea name="message" placeholder="Présentez vos services ou qualifications (facultatif)"></textarea>
        <button type="submit">Envoyer la demande</button>
    </form>
</section>
</main>

<footer>
    <p>&copy; 2025 Business Care. Tous droits réservés.</p>
</footer>

</body>
</html>
