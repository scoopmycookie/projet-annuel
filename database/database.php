<?php

$host = 'localhost';
$dbname = 'business1_care';
$username = 'root'; // Remplacez par votre utilisateur MySQL
$password = 'root'; // Remplacez par votre mot de passe MySQL

// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

?>