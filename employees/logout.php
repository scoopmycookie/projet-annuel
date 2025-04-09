<?php
session_start();

// 🔐 Supprime toutes les variables de session
$_SESSION = [];

// 💣 Détruit la session
session_destroy();

// ✅ Redirige vers le login public avec un paramètre
header("Location: ../public/login.php?logged_out=1");
exit();
