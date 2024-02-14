<?php
// Redirige inmediatamente a autologin.php con el método GET y la contraseña safelock
header('Location: ../autologin.php?password=safelock');
exit;
?>
