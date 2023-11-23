import netifaces
import subprocess
import requests
import time

def obtener_estadisticas():
    interfaz_deseada = "eth0"
    direcciones = netifaces.ifaddresses(interfaz_deseada)
    direccion_ip = direcciones[netifaces.AF_INET][0]['addr']

    pihole_url = f"http://{direccion_ip}/admin/api.php"

    comando = "sudo cat /etc/pihole/setupVars.conf | grep PASSWORD"
    pre_API = subprocess.check_output(comando, shell=True, universal_newlines=True)
    api_key = pre_API.replace("WEBPASSWORD=", "").strip()

    parametros = {
        'summary': '',
        'auth': api_key
    }
    response = requests.get(pihole_url, params=parametros)
    datos = response.json()

    consultas_totales = datos['dns_queries_today']
    porcentaje_bloqueo = datos['ads_percentage_today']
    consultas_bloqueadas = datos['ads_blocked_today']
    dominios_en_listas = datos['domains_being_blocked']

    return consultas_totales, porcentaje_bloqueo, consultas_bloqueadas, dominios_en_listas

def leer_archivos():
    tiempo_inicio = time.time()
    tiempo_limite = 60  # 60 segundos = 1 minuto

    while time.time() - tiempo_inicio <= tiempo_limite:
        # Obtener los resultados
        resultados = obtener_estadisticas()

        # Almacena los resultados en variables individuales
        consultas_totales, porcentaje_bloqueo, consultas_bloqueadas, dominios_en_listas = resultados

        # Crear o abrir archivos de texto y escribir los nuevos valores
        with open("/var/www/html/admin/estadisticas/stats/consultas_totales.txt", "w") as file:
            file.write(str(consultas_totales))

        with open("/var/www/html/admin/estadisticas/stats/porcentaje_bloqueo.txt", "w") as file:
            file.write(str(porcentaje_bloqueo) + "%")

        with open("/var/www/html/admin/estadisticas/stats/consultas_bloqueadas.txt", "w") as file:
            file.write(str(consultas_bloqueadas))

        with open("/var/www/html/admin/estadisticas/stats/dominios_en_listas.txt", "w") as file:
            file.write(str(dominios_en_listas))

        time.sleep(2)


# Iniciar la función leer_archivos()
leer_archivos()

