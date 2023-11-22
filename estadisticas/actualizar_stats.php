<?php
if (isset($_GET['archivo'])) {
    $archivosPermitidos = ['dominios_en_listas.txt', 'consultas_bloqueadas.txt', 'porcentaje_bloqueo.txt', 'consultas_totales.txt'];
    
    $archivoSolicitado = $_GET['archivo'];

    // Verificar si el archivo solicitado está permitido
    if (in_array($archivoSolicitado, $archivosPermitidos)) {
        $rutaArchivo = "/var/www/html/admin/estadisticas/stats/" . $archivoSolicitado;
        $contenido = file_get_contents($rutaArchivo);
        echo $contenido;
        exit(); // Detener la ejecución del resto del código
    }
}
// Si no se proporciona un nombre de archivo válido, devolver un mensaje de error
echo "Error: Archivo no válido";
?>

