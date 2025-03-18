<?php
session_start();
require_once("../db/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hachage du mot de passe
    $role = $_POST["role"]; // Récupération du rôle choisi

    // Insérer l'utilisateur en base
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt->execute([$name, $email, $password, $role])) {
        // Stocker les infos de session
        $_SESSION["user_id"] = $pdo->lastInsertId();
        $_SESSION["name"] = $name;
        $_SESSION["role"] = $role;

        // Rediriger vers le bon espace selon le rôle
        if ($role == "admin") {
            header("Location: ../admin/dashboard.php");
        } elseif ($role == "employe") {
            header("Location: ../employe/dashboard_employe.php");
        } else {
            header("Location: ../prestataire/dashboard_prestataire.php");
        }
        exit();
    } else {
        echo "<script>alert('Erreur lors de l\'inscription.'); window.location.href='../register.php';</script>";
    }
}
?>
