<?php
require_once 'db.php';

function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        return true;
    }
    return false;
}

function isLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}

function logout() {
    session_start();
    session_destroy();
}
?>
