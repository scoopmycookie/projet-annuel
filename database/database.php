<?php

$host = 'localhost';
$dbname = 'businesscare';
$username = 'root'; 
$password = 'root'; 


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

?>