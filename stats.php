<?php
/*   Pi-hole: A black hole for Internet advertisements
*    (c) 2017 Pi-hole, LLC (https://pi-hole.net)
*    Network-wide ad blocking via your own hardware.
*
*    This file is copyright under the latest version of the EUPL.
*    Please see LICENSE file for your rights under this license.
*/

$indexpage = true;
require 'scripts/pi-hole/php/header_authenticated2.php';
require_once 'scripts/pi-hole/php/gravity.php';


// Incluir lógica para manejar la solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiarEstado'])) {
    $statusFile = '/var/www/html/admin/scripts/pi-hole/php/control_parental/parental_control_status.txt';
    $status = trim(file_get_contents($statusFile));
    $newStatus = $status === "Habilitado" ? "Deshabilitado" : "Habilitado";
    file_put_contents($statusFile, $newStatus);

    // Opcionalmente, redirige de nuevo a la misma página para evitar reenvíos de formulario
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas SafeLock</title>

    <link rel="stylesheet" href="estadisticas/estilos.css">


</head>


<body>

    <div class="contenedor-estadisticas">
        <div class="titulo text-center">
            <img class="logo-img" src="img/Logos_SafeLock/SafeLock.jpeg" alt="OpenLock logo">
            <h1>Estadísticas SafeLock</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <!-- small box -->
                <div class="small-box bg-aqua no-user-select" id="total_queries" title="only A + AAAA queries">
                    <div class="inner">
                        <p>Total queries</p>
                        <h3 class="statistic"><span id="dns_queries_today">---</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <!-- small box -->
                <div class="small-box bg-red no-user-select">
                    <div class="inner">
                        <p>Queries Blocked</p>
                        <h3 class="statistic"><span id="queries_blocked_today">---</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <!-- small box -->
                <div class="small-box bg-yellow no-user-select">
                    <div class="inner">
                        <p>Percentage Blocked</p>
                        <h3 class="statistic"><span id="percentage_blocked_today">---</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <!-- small box -->
                <div class="small-box bg-green no-user-select" title="<?php echo gravity_last_update(); ?>">
                    <div class="inner">
                        <p>Domains on Adlists</p>
                        <h3 class="statistic"><span id="domains_being_blocked">---</span></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section>
<?php
$statusFile = '/var/www/html/admin/scripts/pi-hole/php/control_parental/parental_control_status.txt';

$status = trim(file_get_contents($statusFile));

// Determinar el texto y el color del botón basado en el estado
$buttonText = $status === "Habilitado" ? "Deshabilitar" : "Habilitar";
$buttonColor = $status === "Habilitado" ? "btn-red" : "btn-green";
?>

    <div class="control-parental-container">
        <form id="formControlParental" method="post" style="display:none;">
            <input type="hidden" name="cambiarEstado" value="1">
        </form>
        <h1 class="control-parental-title">Control Parental</h1>
        <button id="parentalControlButton" class="btn <?php echo $buttonColor; ?>"><?php echo $buttonText; ?> Control Parental</button>
    </div>


    <div class="button-container text-center">
        <a href="index.php" class="btn btn-green">Ver más estadísticas</a>
        <div class="button-separator"></div>
        <a href="#" onclick="abrirVentanaEmergente()" class="btn btn-blue">Agregar Correo</a>
    </div>

    <script>
        function abrirVentanaEmergente() {
            // Abre la ventana emergente centrada en la pantalla
            var ventanaEmergente = window.open('scripts/pi-hole/php/CORREO/registro_correo.php', 'RegistroCorreo', 'width=600, height=600, top=' + (screen.height/2 - 200) + ', left=' + (screen.width/2 - 300));

        }
    </script>
    <script>
        document.getElementById('parentalControlButton').addEventListener('click', function() {
            document.getElementById('formControlParental').submit();
        });
    </script>


    </section>
</body>

<script src="<?php echo fileversion('scripts/pi-hole/js/index.js'); ?>"></script>
