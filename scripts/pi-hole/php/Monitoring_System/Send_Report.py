import uuid
from pymongo import MongoClient
from datetime import datetime
import os

#Se obtiene la mac
mac = uuid.getnode()
mac_address = ':'.join([f"{(mac >> elements) & 0xff:02x}" for elements in reversed(range(0, 6*8, 8))])
mac_in_text = str(mac_address)

#Conexión con la base de datos
uri = os.environ.get("URI")
client = MongoClient(uri)
db = client["SafeLocks"]
coleccion = db["safelock"]

def enviar_heartbeat():
    mac = mac_in_text
    SafeLock = coleccion.find_one({"mac": mac})
    if SafeLock is None:
        print()
    else:
        hora_actual_utc = datetime.utcnow()
        
        coleccion.update_one(
            {"mac": mac},
            {"$set": {"ultimo_reporte": hora_actual_utc}},
            upsert=True
        )

enviar_heartbeat()
