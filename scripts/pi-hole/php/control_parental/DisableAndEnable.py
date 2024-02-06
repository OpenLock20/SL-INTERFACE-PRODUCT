import sqlite3
import os

# Ubicación de la base de datos de Pi-hole y el archivo de listas de control parental
gravity_db_path = "/etc/pihole/gravity.db"
parental_lists_file = "/var/www/html/admin/scripts/pi-hole/php/control_parental/parental_control_lists.txt"

def read_list_from_file(file_path):
    """Lee las URLs de las listas de un archivo."""
    with open(file_path, 'r') as file:
        return file.read().splitlines()

def check_lists_status():
    """Verifica si al menos una de las listas de control parental está habilitada."""
    conn = sqlite3.connect(gravity_db_path)
    cursor = conn.cursor()

    parental_lists = read_list_from_file(parental_lists_file)
    for list_url in parental_lists:
        cursor.execute("SELECT enabled FROM adlist WHERE address = ?", (list_url,))
        result = cursor.fetchone()
        if result:
            # Retorna True si alguna lista está habilitada
            if result[0] == 1:
                return True
    # Retorna False si todas las listas están deshabilitadas o no existen
    return False

def toggle_parental_control():
    """Cambia el estado de las listas de control parental en gravity.db."""
    enable = not check_lists_status()  # Cambia el estado basado en el estado actual

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
    if enable:
        print("Control parental habilitado.")
    else:
        print("Control parental deshabilitado.")

if __name__ == "__main__":
    toggle_parental_control()

