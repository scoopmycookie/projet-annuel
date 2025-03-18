<?php
session_start();
require_once("../db/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    // Vérifier que la connexion à la base fonctionne
    if (!$pdo) {
        die("Erreur de connexion à la base de données.");
    }

    // Récupérer l'utilisateur
    $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier que le mot de passe en base est bien haché
        if (strlen($user["password"]) < 30) {
            die("Le mot de passe en base n'est pas haché. Veuillez rehasher les mots de passe.");
        }

        // Vérifier le mot de passe
        if (password_verify($password, $user["password"])) {
            // Stocker les infos de session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            // Vérification de la session
            error_log("Utilisateur connecté - Email: $email - Role: " . $_SESSION["role"]);

            // Afficher le contenu de la session pour le debug (à retirer en production)
            var_dump($_SESSION);
            
            // Redirection selon le rôle
            switch ($user["role"]) {
                case "admin":
                    header("Location: ../admin/dashboard.php");
                    break;
                case "employe":
                    header("Location: ../employe/dashboard_employe.php");
                    break;
                case "prestataire":
                    header("Location: ../prestataire/dashboard_prestataire.php");
                    break;
                default:
                    header("Location: ../index.php");
                    break;
            }
            exit();
        } else {
            error_log("Échec de connexion : mot de passe incorrect pour $email");
            echo "<script>alert('Mot de passe incorrect !'); window.location.href='../login.php';</script>";
        }
    } else {
        error_log("Échec de connexion : utilisateur non trouvé pour $email");
        echo "<script>alert('Utilisateur non trouvé !'); window.location.href='../login.php';</script>";
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>
