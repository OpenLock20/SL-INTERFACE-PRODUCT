import sqlite3
import os
import time
from datetime import datetime


gravity_db_path = "/etc/pihole/gravity.db"
parental_lists_file = "/var/www/html/admin/scripts/pi-hole/php/control_parental/parental_control_lists.txt"
status_file_path = "/var/www/html/admin/scripts/pi-hole/php/control_parental/parental_control_status.txt"
schedule_file_path = "/var/www/html/admin/scripts/pi-hole/php/control_parental/horario_control.txt"

def read_list_from_file(file_path):
    with open(file_path, 'r') as file:
        return file.read().splitlines()

def read_status_from_file():
    with open(status_file_path, 'r') as file:
        status = file.read().strip()
    return status.lower() == "habilitado"

def is_within_scheduled_time():
    try:
        with open(schedule_file_path, 'r') as file:
            schedule = file.read().strip()
        parts = schedule.split(", ")
        inicio = parts[0].split(": ")[1]  # Extrae HH:MM para inicio
        fin = parts[1].split(": ")[1]  # Extrae HH:MM para fin
        now = datetime.now().time()
        start_time = datetime.strptime(inicio, "%H:%M").time()
        end_time = datetime.strptime(fin, "%H:%M").time()

        if start_time <= now <= end_time:
            return True
        else:
            return False
    except Exception as e:
        return False

def toggle_parental_control(enable):
    if enable and not is_within_scheduled_time():
        return

    conn = sqlite3.connect(gravity_db_path)
    cursor = conn.cursor()

    parental_lists = read_list_from_file(parental_lists_file)
    needs_update = False

    for list_url in parental_lists:
        cursor.execute("SELECT id FROM adlist WHERE address = ?", (list_url,))
        result = cursor.fetchone()
        if result:

            cursor.execute("UPDATE adlist SET enabled = ? WHERE address = ?", (1 if enable else 0, list_url))
        else:

            if enable:  # Solo inserta si estamos habilitando
                cursor.execute("INSERT INTO adlist (address, enabled) VALUES (?, 1)", (list_url,))
                needs_update = True  # Requiere actualización ya que se agregó una nueva lista

    conn.commit()
    conn.close()

    if needs_update:
        os.system("pihole -g")
    else:
        os.system("pihole restartdns reload-lists")

    print("Control parental {}.".format("habilitado" if enable else "deshabilitado"))

def main():
    last_status = None  # Almacena el último estado leído para detectar cambios
    start_time = time.time()
    while time.time() - start_time < 60:  # Ejecuta durante un minuto
        current_status = read_status_from_file() and is_within_scheduled_time()
        if current_status != last_status:  # Verifica si el estado ha cambiado
            toggle_parental_control(current_status)
            last_status = current_status  # Actualiza el último estado conocido
        time.sleep(1)  # Reduce el tiempo de espera para una detección más rápida de cambios

if __name__ == "__main__":
    main()
