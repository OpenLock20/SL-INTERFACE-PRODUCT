import uuid
import requests
import subprocess
from pymongo import MongoClient

# Conexión con la base
client = MongoClient("mongodb+srv://openlock20oficial:wMcTVR5CnvgKPKrr@safelocks.4kf2jzo.mongodb.net/?retryWrites=true&w=majority")
db = client["SafeLocks"]
coleccion = db["safelock"]

def formatear_id_teamviewer(id_teamviewer):
    # Invierte la cadena, inserta guiones cada 3 caracteres y vuelve a invertir
    return '-'.join(id_teamviewer[::-1][i:i+3] for i in range(0, len(id_teamviewer), 3))[::-1]

def obtener_id_teamviewer():
    try:
        resultado = subprocess.check_output(
            "teamviewer info | grep -A 1 'TeamViewer ID:' | tail -n 1 | awk '{print $1}'", 
            shell=True
        )
        id_sin_formato = resultado.decode('utf-8').strip()
        return formatear_id_teamviewer(id_sin_formato)
    except subprocess.CalledProcessError:
        print("No se pudo obtener el ID de TeamViewer.")
        return None

def verificar_y_actualizar_SafeLock(mac, ip_publica, id_teamviewer):
    # Buscar si el SafeLock ya está en la base
    SafeLock = coleccion.find_one({"mac": mac})

    # Agrega el SafeLock si no existe en la base
    if SafeLock is None:
        coleccion.insert_one({"mac": mac, "ip_publica": ip_publica, "id_teamviewer": id_teamviewer})
        print("Nuevo dispositivo añadido.")
    else:
        cambios = {}
        if SafeLock['ip_publica'] != ip_publica:
            cambios['ip_publica'] = ip_publica
        if 'id_teamviewer' not in SafeLock or SafeLock['id_teamviewer'] != id_teamviewer:
            cambios['id_teamviewer'] = id_teamviewer

        if cambios:
            coleccion.update_one({"mac": mac}, {"$set": cambios})
            print("Información actualizada.")
        else:
            print("No hay cambios en la IP pública ni en el ID de TeamViewer. No se requiere acción.")

# Obtiene la MAC y la IP pública
mac = uuid.getnode()
mac_address = ':'.join([f"{(mac >> elements) & 0xff:02x}" for elements in reversed(range(0, 6*8, 8))])
mac_utilizable = str(mac_address)
IP = requests.get('http://checkip.amazonaws.com').text.strip()

# Obtiene el ID de TeamViewer
id_teamviewer = obtener_id_teamviewer()

verificar_y_actualizar_SafeLock(mac_utilizable, IP, id_teamviewer)
