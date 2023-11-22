<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Dominios</title>
</head>
<body>
    <h1>Estadísticas de Dominios</h1>
    <p id="dominios"></p>
    <p id="consultasBloqueadas"></p>
    <p id="porcentajeBloqueo"></p>
    <p id="consultasTotales"></p>

    <script>
        function actualizarContenido(archivo, elementoId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById(elementoId).innerText = archivo + ': ' + xhr.responseText;
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
</body>
</html>

