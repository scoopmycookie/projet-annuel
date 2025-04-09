<?php
session_start();
require '../database/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

// Récupérer l'entreprise du client connecté
$stmt = $conn->prepare("SELECT company FROM users WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
$company = $data['company'];

if (!isset($_GET['id'])) {
    die("ID manquant.");
}

$emp_id = intval($_GET['id']);

// Supprimer seulement si le collaborateur appartient à l'entreprise
$delete = $conn->prepare("DELETE FROM users WHERE id = ? AND company = ? AND role = 'employee'");
$delete->bind_param("is", $emp_id, $company);

if ($delete->execute()) {
    header("Location: dashboard.php?success=Collaborateur supprimé");
} else {
    echo "Erreur : " . $delete->error;
}
exit();
