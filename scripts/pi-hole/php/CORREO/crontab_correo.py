import subprocess

archivo_conf = "configuracion_correo.txt"

with open(archivo_conf, 'r') as archivo:
    conf_crontab = archivo.read().strip()

# Comando para agregar la tarea al crontab
cron_command = f'echo "{conf_crontab}" | crontab -'

# Verificar si el crontab ya contiene las tareas
try:
    crontab_content = subprocess.check_output(['crontab', '-l'], universal_newlines=True)
    if conf_crontab in crontab_content:
        print("Las tareas ya están configuradas en el crontab.")
    else:
        cronjob = f"""* * * * * python3 /var/www/html/admin/scripts/pi-hole/php/CORREO/crontab_correo.py
{conf_crontab}"""

        # Agregar el cronjob al crontab
        subprocess.run(cron_command, shell=True, check=True)

        print("Tareas configuradas en el crontab.")
except subprocess.CalledProcessError as e:
    print(f"Error al obtener el crontab: {e}")
