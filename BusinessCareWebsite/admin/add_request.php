
<?php
require_once '../includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO registration_requests (
        company_name, siret, representative_name, email, password, phone, website,
        address_street, address_city, address_postal_code, address_country, role, message
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $_POST['company_name'], $_POST['siret'], $_POST['representative_name'],
        $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['phone'], $_POST['website'], $_POST['address_street'],
        $_POST['address_city'], $_POST['address_postal_code'], $_POST['address_country'],
        $_POST['role'], $_POST['message']
    ]);

    header("Location: validate_accounts.php");
    exit;
}
?>

<section class="form-section">
    <h2>Ajouter une demande manuellement</h2>
    <form method="POST" class="quote-form">
        <input type="text" name="company_name" placeholder="Nom de l'entreprise" required>
        <input type="text" name="siret" placeholder="Numéro SIRET" required>
        <input type="text" name="representative_name" placeholder="Nom du représentant" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="text" name="phone" placeholder="Téléphone">
        <input type="text" name="website" placeholder="Site web">
        <input type="text" name="address_street" placeholder="Adresse">
        <input type="text" name="address_city" placeholder="Ville">
        <input type="text" name="address_postal_code" placeholder="Code postal">
        <input type="text" name="address_country" placeholder="Pays">
        <select name="role" required>
            <option value="">-- Sélectionner un rôle --</option>
            <option value="client">Client</option>
            <option value="provider">Prestataire</option>
        </select>
        <textarea name="message" placeholder="Message (facultatif)"></textarea>
        <button type="submit">Ajouter la demande</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
