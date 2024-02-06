import sqlite3
import os
import time

# Ubicación de la base de datos de Pi-hole y el archivo de listas de control parental
gravity_db_path = "/etc/pihole/gravity.db"
parental_lists_file = "/var/www/html/admin/scripts/pi-hole/php/control_parental/parental_control_lists.txt"
# Ruta al archivo donde se guardará el estado del control parental
status_file_path = "/var/www/html/admin/scripts/pi-hole/php/control_parental/parental_control_status.txt"

def read_list_from_file(file_path):
    """Lee las URLs de las listas de un archivo."""
    with open(file_path, 'r') as file:
        return file.read().splitlines()

def read_status_from_file():
    """Lee el estado del control parental desde un archivo."""
    with open(status_file_path, 'r') as file:
        status = file.read().strip()
    return status.lower() == "habilitado"

def toggle_parental_control(enable):
    """Cambia el estado de las listas de control parental en gravity.db basado en el estado leído del archivo."""
    conn = sqlite3.connect(gravity_db_path)
    cursor = conn.cursor()

    parental_lists = read_list_from_file(parental_lists_file)

    for list_url in parental_lists:
        cursor.execute("SELECT id FROM adlist WHERE address = ?", (list_url,))
        result = cursor.fetchone()
        if result:
            # La lista existe, entonces actualiza su estado
            cursor.execute("UPDATE adlist SET enabled = ? WHERE address = ?", (1 if enable else 0, list_url))
        else:
            # La lista no existe, entonces la inserta y la habilita
            if enable:  # Solo inserta si estamos habilitando
                cursor.execute("INSERT INTO adlist (address, enabled) VALUES (?, 1)", (list_url,))

    conn.commit()
    conn.close()

    os.system("pihole restartdns reload-lists")
    print("Control parental {}.".format("habilitado" if enable else "deshabilitado"))

def main():
    last_status = None  # Almacena el último estado leído para detectar cambios
    start_time = time.time()
    while time.time() - start_time < 60:  # Ejecuta durante un minuto
        current_status = read_status_from_file()
        if current_status != last_status:  # Verifica si el estado ha cambiado
            toggle_parental_control(current_status)
            last_status = current_status  # Actualiza el último estado conocido
        time.sleep(1)  # Reduce el tiempo de espera para una detección más rápida de cambios

if __name__ == "__main__":
    main()
