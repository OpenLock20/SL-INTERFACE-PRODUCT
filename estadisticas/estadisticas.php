<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Dominios</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor-estadisticas">
        <h1>Estadísticas de Dominios</h1>
        <div id="dominios" class="estadistica"></div>
        <div id="consultasBloqueadas" class="estadistica"></div>
        <div id="porcentajeBloqueo" class="estadistica"></div>
        <div id="consultasTotales" class="estadistica"></div>

        <script>
            function actualizarContenido(archivo, elementoId, nombreLegible) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById(elementoId).innerHTML = '<span class="nombre-archivo">' + nombreLegible + '</span>: ' + xhr.responseText;
                    }
                };
                xhr.open('GET', 'actualizar_stats.php?archivo=' + archivo, true);
                xhr.send();
            }

            // Actualizar cada segundo
            setInterval(function() {
                actualizarContenido('dominios_en_listas.txt', 'dominios', 'Dominios en Listas');
                actualizarContenido('consultas_bloqueadas.txt', 'consultasBloqueadas', 'Consultas Bloqueadas');
                actualizarContenido('porcentaje_bloqueo.txt', 'porcentajeBloqueo', 'Porcentaje de Bloqueo');
                actualizarContenido('consultas_totales.txt', 'consultasTotales', 'Consultas Totales');
            }, 1000);

            // Actualizar por primera vez al cargar la página
            actualizarContenido('dominios_en_listas.txt', 'dominios', 'Dominios en Listas');
            actualizarContenido('consultas_bloqueadas.txt', 'consultasBloqueadas', 'Consultas Bloqueadas');
            actualizarContenido('porcentaje_bloqueo.txt', 'porcentajeBloqueo', 'Porcentaje de Bloqueo');
            actualizarContenido('consultas_totales.txt', 'consultasTotales', 'Consultas Totales');
        </script>
    </div>
</body>
</html>

