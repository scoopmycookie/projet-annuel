<?php
require_once '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'] ?? '';
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT provider_id FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $provider_id = $stmt->fetchColumn();

    if (!$provider_id) {
        echo "Erreur : ce service n’a pas de prestataire associé.";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO service_reviews (service_id, user_id, provider_id, rating, comment)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$service_id, $user_id, $provider_id, $rating, $comment]);

    header("Location: services.php");
    exit;
}
?>
