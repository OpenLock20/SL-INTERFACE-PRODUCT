import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import requests
import subprocess
import netifaces

def obtener_estadisticas():

    interfaz_deseada = "eth0"
    direcciones = netifaces.ifaddresses(interfaz_deseada)
    direccion_ip = direcciones[netifaces.AF_INET][0]['addr']

    pihole_url = f"http://{direccion_ip}/admin/api.php"


    comando = "sudo cat /etc/pihole/setupVars.conf | grep PASSWORD"

    pre_API = subprocess.check_output(comando, shell=True, universal_newlines=True)


    api_key = pre_API.replace("WEBPASSWORD=","").strip()

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



def enviar_correo(consultas, porcentaje_bloqueo, consultas_bloqueadas, dominios_en_listas):
    # Configuración del servidor de correo
    servidor_correo = "smtp.office365.com"
    puerto_correo = 587
    usuario_correo = ""
    contraseña_correo = ""

    archivo_correo = "correo_almacenado.txt"

    with open(archivo_correo, 'r') as archivo:
        correo_usuario = archivo.read()


    # Configuración del destinatario y remitente
    remitente = ""
    destinatario = correo_usuario

    # Configuración del mensaje HTML
    cuerpo_mensaje = (
        f"<html>"
        f"<head>"
        f"<style>"
        f"body {{ font-family: 'Arial', sans-serif; }}"
        f".info-box {{ background-color: #f2f2f2; padding: 10px; margin-bottom: 10px; border-radius: 10px; }}"
        f".container-box {{ background-color: #d9d9d9; padding: 10px; border-radius: 10px; }}"
        f".stat-box {{ background-color: #fff; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 10px; }}"
        f"</style>"
        f"</head>"
        f"<body>"
        f"<div class='container-box'>"
        f"<h2 style='color: #333;'>Estadísticas Pi-hole</h2>"
        f"<div class='stat-box'>"
        f"<p><strong>Consultas totales:</strong> {consultas}</p>"
        f"</div>"
        f"<div class='stat-box'>"
        f"<p><strong>Porcentaje de bloqueo:</strong> {porcentaje_bloqueo}%</p>"
        f"</div>"
        f"<div class='stat-box'>"
        f"<p><strong>Consultas bloqueadas:</strong> {consultas_bloqueadas}</p>"
        f"</div>"
        f"<div class='stat-box'>"
        f"<p><strong>Dominios en listas de bloqueo:</strong> {dominios_en_listas}</p>"
        f"</div>"
        f"</div>"
        f"</body>"
        f"</html>"
    )

    mensaje = MIMEMultipart()
    mensaje['From'] = remitente
    mensaje['To'] = destinatario
    mensaje['Subject'] = "Estadísticas desde Pi-hole"
    mensaje.attach(MIMEText(cuerpo_mensaje, 'html'))

    # Iniciar sesión en el servidor de correo y enviar el mensaje
    with smtplib.SMTP(servidor_correo, puerto_correo) as servidor:
        servidor.starttls()
        servidor.login(usuario_correo, contraseña_correo)
        servidor.sendmail(remitente, destinatario, mensaje.as_string())

if __name__ == "__main__":
    consultas, bloqueo, consultas_bloqueadas, dominios_en_listas = obtener_estadisticas()

    enviar_correo(consultas, bloqueo, consultas_bloqueadas, dominios_en_listas)
