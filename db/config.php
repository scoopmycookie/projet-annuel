<?php
$host = "localhost";
$dbname = "businesscare";
$username = "root";  // Sous MAMP, c'est "root"
$password = "root";  // Sous MAMP, le mot de passe est aussi "root"

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
