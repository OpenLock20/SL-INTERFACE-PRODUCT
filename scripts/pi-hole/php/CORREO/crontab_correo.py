import subprocess

archivo_conf = "configuracion_correo.txt"

with open(archivo_conf, 'r') as archivo:
    conf_crontab = archivo.read().strip()

# Verificar si el crontab ya contiene las tareas
try:
    crontab_content = subprocess.check_output(['crontab', '-l'], universal_newlines=True)
    if conf_crontab in crontab_content:
        print("Las tareas ya están configuradas en el crontab.")
    else:
        cronjob = f"""* * * * * python3 /var/www/html/admin/scripts/pi-hole/php/CORREO/crontab_correo.py
{conf_crontab}"""
        
        with open('/tmp/cronjob', 'w') as cronfile:
            cronfile.write(cronjob + '\n')
        
        subprocess.call(['crontab', '/tmp/cronjob'])
        print("Tareas configuradas en el crontab.")
except subprocess.CalledProcessError as e:
    print(f"Error al obtener el crontab: {e}")
