<?php
$action = $_GET['action'] ?? ''; // Recoger acción de la URL

if ($action === 'enable' || $action === 'disable') {
    $command = escapeshellcmd("/usr/bin/python3 /var/www/html/admin/scripts/pi-hole/php/control_parental/DisableAndEnable.py " . $action);
    shell_exec($command);
    header("Location: /admin/index.php"); // Redirige
?>
