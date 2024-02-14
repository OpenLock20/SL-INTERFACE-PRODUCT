<?php
session_start(); // Inicia la sesión PHP al principio del archivo
require_once 'scripts/pi-hole/php/password.php'; // Asegúrate de que la ruta al archivo password.php sea correcta

if (isset($_GET['password']) && $_GET['password'] === 'safelock') {
    $_SESSION['auth'] = true;
    $_SESSION['safelock_access'] = true; // Indica el acceso safelock
    header('Location: stats.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>
