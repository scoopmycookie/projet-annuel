<?php
require_once '../includes/db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$id]);
$company = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update = $pdo->prepare("UPDATE companies SET name=?, siret=?, industry=?, email=?, phone=?, website=?, address_street=?, address_city=?, address_postal_code=?, address_country=?, representative_name=?, employees=? WHERE id=?");
    $update->execute([
        $_POST['name'], $_POST['siret'], $_POST['industry'], $_POST['email'], $_POST['phone'],
        $_POST['website'], $_POST['address_street'], $_POST['address_city'], $_POST['address_postal_code'],
        $_POST['address_country'], $_POST['representative_name'], $_POST['employees'], $id
    ]);

    header("Location: companies.php");
    exit;
}
?>
