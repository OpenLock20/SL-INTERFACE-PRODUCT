import subprocess
import os


user = os.getlogin()

archivo_conf = "/var/www/html/admin/scripts/pi-hole/php/CORREO/configuracion_correo.txt"
temp_file = "/tmp/temp_crontab.txt"

with open(archivo_conf, 'r') as archivo:
    conf_crontab = archivo.read().strip()

# Guardar el contenido del crontab en un archivo temporal
with open(temp_file, 'w') as temp:
    temp.write(conf_crontab + '\n') 


os.chown(temp_file, os.getuid(), os.getgid())

cron_command = f'crontab -u {user} {temp_file}'

# Verificar si el crontab del usuario normal ya contiene las tareas
try:
    crontab_content = subprocess.check_output(['crontab', '-l', '-u', user_normal], universal_newlines=True)
    if conf_crontab in crontab_content:
        print("Las tareas ya están configuradas en el crontab del usuario normal.")
    else:
        cronjob = f"""* * * * * python3 /var/www/html/admin/scripts/pi-hole/php/CORREO/crontab_correo.py
{conf_crontab}"""


        subprocess.run(cron_command, shell=True, check=True)

        print("Tareas configuradas en el crontab del usuario normal.")
except subprocess.CalledProcessError as e:
    print(f"Error al obtener el crontab del usuario normal: {e}")

