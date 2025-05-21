<?php
if (!isset($_SESSION)) session_start();

$lang = $_SESSION['lang'] ?? 'fr';
$lang_file = __DIR__ . '/../lang/' . $lang . '.php';

if (file_exists($lang_file)) {
    $trans = include($lang_file);
} else {
    $trans = include(__DIR__ . '/../lang/fr.php');
}
