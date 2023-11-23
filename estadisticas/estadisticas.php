<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas SafeLock</title>

    <link rel="apple-touch-icon" href="../img/Logos_OpenLock/OpenLock_logo_No_Background.jpeg" sizes="180x180">
    <link rel="icon" href="" sizes="32x32" type="image/png">
    <link rel="icon" href="../img/Logos_OpenLock/OpenLock_logo_No_Background.jpeg" sizes="16x16" type="image/png">
    <link rel="manifest" href="../img/Logos_OpenLock/OpenLock_logo_No_Background.jpeg">
    <link rel="mask-icon" href="../img/Logos_OpenLock/OpenLock_logo_No_Background.jpeg" color="<?php echo $theme_color; ?>">
    <link rel="shortcut icon" href="../img/Logos_OpenLock/OpenLock_logo_No_Background.jpeg">
    <meta name="msapplication-TileColor" content="<?php echo $theme_color; ?>">
    <meta name="msapplication-TileImage" content="../img/Logos_OpenLock/OpenLock_logo_No_Background.jpeg">

    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">

    <!-- Agrega la referencia a jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/t1u1qOzPq6N73dLHT2+nss+xdlfxMwI1lAxpM=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="contenedor-estadisticas">
		<div class="titulo text-center">
			<center><img class="logo-img" src="../img/Logos_SafeLock/SafeLock.jpeg" alt="OpenLock logo"></center>
        	<h1>Estadísticas SafeLock</h1>
    	</div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-aqua no-user-select">
                    <div class="inner">
                        <p>Consultas Totales</p>
                        <h3 id="consultasTotales" class="estadistica"></h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-red no-user-select">
                    <div class="inner">
                        <p>Consultas Bloqueadas</p>
                        <h3 id="consultasBloqueadas" class="estadistica"></h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-paper"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-yellow no-user-select">
                    <div class="inner">
                        <p>Porcentaje Bloqueado</p>
                        <h3 id="porcentajeBloqueo" class="estadistica"></h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-green no-user-select">
                    <div class="inner">
                        <p>Dominios En Listas</p>
                        <h3 id="dominios" class="estadistica"></h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function actualizarContenido(archivo, elementoId) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById(elementoId).innerHTML = xhr.responseText;
                    }
                };
                xhr.open('GET', 'actualizar_stats.php?archivo=' + archivo, true);
                xhr.send();
            }

            // Actualizar cada segundo
            setInterval(function() {
                actualizarContenido('dominios_en_listas.txt', 'dominios');
                actualizarContenido('consultas_bloqueadas.txt', 'consultasBloqueadas');
                actualizarContenido('porcentaje_bloqueo.txt', 'porcentajeBloqueo');
                actualizarContenido('consultas_totales.txt', 'consultasTotales');
            }, 1000);

            // Actualizar por primera vez al cargar la página
            actualizarContenido('dominios_en_listas.txt', 'dominios');
            actualizarContenido('consultas_bloqueadas.txt', 'consultasBloqueadas');
            actualizarContenido('porcentaje_bloqueo.txt', 'porcentajeBloqueo');
            actualizarContenido('consultas_totales.txt', 'consultasTotales');
        </script>
    </div>
	<section>
		
		<div class="button-container text-center">
			<a href="../login.php" class="btn btn-green">Iniciar Sesión</a>
			<div class="button-separator"></div>
	   		<a href="#" onclick="abrirVentanaEmergente()" class="btn btn-blue">Agregar Correo</a>
		</div>

		<script>
			function abrirVentanaEmergente() {
				// Abre la ventana emergente centrada en la pantalla
				var ventanaEmergente = window.open('../scripts/pi-hole/php/CORREO/registro_correo.php', 'RegistroCorreo', 'width=600, height=400, top=' + (screen.height/2 - 200) + ', left=' + (screen.width/2 - 300));

			}
		</script>
		

	</section>

</body>

</html>

