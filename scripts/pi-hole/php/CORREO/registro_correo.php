<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envío de Reportes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #3c8dbc; /* Azul del CSS proporcionado */
            color: #fff;
            cursor: pointer;
            border: none;
            border-radius: 3px;
        }

        input[type="submit"]:hover {
            background-color: #367fa9; /* Azul más oscuro en hover */
        }

        .registro-exitoso {
            color: #4caf50;
            margin-top: 10px;
        }

        .logo-img {
            max-width: 19%; /* Ajuste del tamaño máximo */
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <img class="logo-img" src="OpenLock_logo_No_Background.jpeg" alt="OpenLock logo">

    <h2>Envío de Reportes</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" required value="<?php echo obtenerCorreoActual(); ?>">

        <label for="frecuencia">Frecuencia de informes:</label>
        <select id="frecuencia" name="frecuencia">
            <option value="0 12 * * * python3 /var/www/html/admin/scripts/pi-hole/php/CORREO/send_email.py" <?php echo (obtenerFrecuenciaActual() == 'diario') ? 'selected' : ''; ?>>Recibir Informe Diario</option>
            <option value="0 12 * * 5 python3 /var/www/html/admin/scripts/pi-hole/php/CORREO/send_email.py" <?php echo (obtenerFrecuenciaActual() == 'semanal') ? 'selected' : ''; ?>>Recibir Informe Semanal</option>
            <option value="0 12 28 * * python3 /var/www/html/admin/scripts/pi-hole/php/CORREO/send_email.py" <?php echo (obtenerFrecuenciaActual() == 'mensual') ? 'selected' : ''; ?>>Recibir Informe Mensual</option>
        </select>

        <input type="submit" value="Aceptar">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $correo = $_POST['correo'];
        $frecuencia = $_POST['frecuencia'];

        // Archivo de almacenamiento de correo
        $archivoCorreo = "correo_almacenado.txt";
        $archivoCorreoAbierto = fopen($archivoCorreo, "w");
        fwrite($archivoCorreoAbierto, $correo . PHP_EOL);
        fclose($archivoCorreoAbierto);

        // Archivo de configuración de frecuencia
        $archivoConfiguracion = "configuracion_correo.txt";
        $archivoConfiguracionAbierto = fopen($archivoConfiguracion, "w");
        fwrite($archivoConfiguracionAbierto, $frecuencia . PHP_EOL);
        fclose($archivoConfiguracionAbierto);

        echo '<p class="registro-exitoso">Configuración Exitosa</p>';

        // Cierra la ventana
        echo '<script>
                setTimeout(function() {
                    window.close();
                }, 1000);
              </script>';
    }

    // Función para obtener el correo actual del archivo
    function obtenerCorreoActual() {
        $archivo = "correo_almacenado.txt";
        if (file_exists($archivo)) {
            $contenido = file_get_contents($archivo);
            return trim($contenido);
        } else {
            return '';
        }
    }

    // Función para obtener la frecuencia actual del archivo de configuración
    function obtenerFrecuenciaActual() {
        $archivo = "configuracion_correo.txt";
        if (file_exists($archivo)) {
            $contenido = file_get_contents($archivo);
            return trim($contenido);
        } else {
            return 'diario'; // Valor predeterminado si el archivo no existe
        }
    }
    ?>
</body>
</html>
