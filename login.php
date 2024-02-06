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

// Go directly to index, if authenticated.
if ($_SESSION['auth']) {
    header('Location: estadisticas/redireccion.php');
    exit;
}

require 'scripts/pi-hole/php/theme.php';
require 'scripts/pi-hole/php/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Coloca aquí tus etiquetas meta, títulos, enlaces a hojas de estilo, etc. -->
</head>
<body class="hold-transition layout-boxed login-page">
    <div class="box login-box">
        <section style="padding: 15px;">
            <div class="login-logo">
                <div class="text-center">
                    <img src="img/SafeLock_logo.jpeg" alt="SafeLock logo" class="loginpage-logo">
                </div>
                <div class="panel-title text-center">
                    <span class="logo-lg" style="font-size: 25px;">OpenLock <b>Ciberseguridad</b></span>
                </div>
            </div>
            <!-- /.login-logo -->
    
            <div class="card">
                <div class="card-body login-card-body">
                    <div id="cookieInfo" class="panel-title text-center text-red" style="font-size: 150%" hidden>
                        Verify that cookies are allowed for <code><?php echo $_SERVER['HTTP_HOST']; ?></code>
                    </div>
                    <?php if ($wrongpassword) { ?>
                        <div class="form-group has-error login-box-msg">
                            <label class="control-label"><i class="fa fa-times-circle"></i> Wrong password!</label>
                        </div>
                    <?php } ?>
    
                    <form action="" id="loginform" method="post">
                        <div class="form-group login-options has-feedback<?php if ($wrongpassword) { ?> has-error<?php } ?>">
                            <div class="pwd-field">
                                <!-- hidden username input field to help password managers to autofill the password -->
                                <input type="text" id="username" value="pi.hole" autocomplete="username" hidden>
                                <input type="password" id="loginpw" name="pw" class="form-control" placeholder="Password" spellcheck="false" autocomplete="current-password" autofocus>
                                <span class="fa fa-key form-control-feedback"></span>
                            </div>
                            <div>
                                <input type="checkbox" id="logincookie" name="persistentlogin">
                                <label for="logincookie">Remember me for 7 days</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i>&nbsp;&nbsp;&nbsp;Log in
                            </button>
                            <!-- New button -->
                            <a href="estadisticas/redireccion.php" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i>&nbsp;&nbsp;&nbsp;Ingresar sin contraseña
                            </a>
                        </div>
                    </form>
                </div>
                <!-- /.login-card-body -->
            </div>
        </section>
    </div>
    
    <script src="<?php echo fileversion('scripts/pi-hole/js/footer.js'); ?>"></script>
</body>
</html>
