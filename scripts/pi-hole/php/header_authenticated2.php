<?php
/*
*  Pi-hole: A black hole for Internet advertisements
*  (c) 2017 Pi-hole, LLC (https://pi-hole.net)
*  Network-wide ad blocking via your own hardware.
*
*  This file is copyright under the latest version of the EUPL.
*  Please see LICENSE file for your rights under this license.
*/

require 'scripts/pi-hole/php/password.php';

if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
    header('Location: estadisticas/redireccion.php');
    exit;
}



require 'scripts/pi-hole/php/auth.php';
require_once 'scripts/pi-hole/php/FTL.php';
require_once 'scripts/pi-hole/php/func.php';




require 'header.php';
?>
<?php
if ($auth) {
    echo "<div id=\"token\" hidden>{$token}</div>";
}
?>
