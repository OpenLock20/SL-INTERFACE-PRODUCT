import os


#Variables
update_email_crontab_file = "/var/www/html/admin/scripts/pi-hole/php/CORREO/configuracion_correo.txt"

# Nombres de archivos crontab
update_repository = "/etc/cron.d/update_repository"
DHCP_conf = "/etc/cron.d/DHCP_conf"
send_email = "/etc/cron.d/send_email"
control_parental = "/etc/cron.d/control_parental"
monitoring_system = "/etc/cron.d/monitoring_system"




# Configuraciones crontab
conf_pull = """# Se hace un pool automatico a las 3 a.m.con el archivo pull.sh
0 3 * * * root /var/www/html/admin/pull.sh
"""

conf_dhcp = """#Aqui se programa que se haga el pool del DHCP
* * * * * root python3 /var/www/html/admin/DHCP_activation.py ; service pihole-FTL restart
#aqui se hace el DHCP starvation
* * * * * root python3 /var/www/html/admin/domain_conf.py
"""

with open(update_email_crontab_file, 'r') as conf:
    conf_cron_email = conf.read()

conf_email = f"""#Configuracion de cada cuanto se quiere recibir el correo
{conf_cron_email}
"""

conf_control_parental = """#se obtienen estadisticas de pi-hole
* * * * * root python3 /var/www/html/admin/scripts/pi-hole/php/control_parental/DisableAndEnable.py
"""

conf_monitoring_system = """#Se corre script de monitoreo
* * * * * root /root/wrapper.sh
"""

# Aplica configuracion a /etc/cron.d/update_repository
if os.path.exists(update_repository):
    with open(update_repository, 'r') as update_repository_existente:
        update_repository_content = update_repository_existente.read()
    if update_repository_content != conf_pull:
        with open(update_repository, 'w') as new_update_repository:
            new_update_repository.write(conf_pull)
else:
    with open(update_repository, 'w') as new_update_repository:
        new_update_repository.write(conf_pull)


# Aplica configuracion a /etc/cron.d/DHCP_conf
if os.path.exists(DHCP_conf):
    with open(DHCP_conf, 'r') as DHCP_conf_existente:
        DHCP_conf_content = DHCP_conf_existente.read()
    if DHCP_conf_content != conf_dhcp:
        with open(DHCP_conf, 'w') as new_DHCP_conf:
            new_DHCP_conf.write(conf_dhcp)
else:
    with open(DHCP_conf, 'w') as new_DHCP_conf:
        new_DHCP_conf.write(conf_dhcp)


# Aplica configuracion a /etc/cron.d/send_email
if os.path.exists(send_email):
    with open(send_email, 'r') as send_email_existente:
        send_email_content = send_email_existente.read()
    if send_email_content != conf_email:
        with open(send_email, 'w') as new_send_email:
            new_send_email.write(conf_email)
else:
    with open(send_email, 'w') as new_send_email:
        new_send_email.write(conf_email)


# Aplica configuracion a /etc/cron.d/control_parental
if os.path.exists(control_parental):
    with open(control_parental, 'r') as control_parental_existente:
        control_parental_content = control_parental_existente.read()
    if control_parental_content != conf_control_parental:
        with open(control_parental, 'w') as new_control_parental:
            new_control_parental.write(conf_control_parental)
else:
    with open(control_parental, 'w') as new_control_parental:
        new_control_parental.write(conf_control_parental)

#Aplica configuración a /etc/cron.d/monitoring_system
if os.path.exists(monitoring_system):
    with open(monitoring_system, 'r') as monitoring_system_existente:
        monitoring_system_content = monitoring_system_existente.read()
    if monitoring_system_content != conf_monitoring_system:
        with open(monitoring_system, 'w') as new_monitoring_system:
            new_monitoring_system.write(conf_monitoring_system)
else:
    with open(monitoring_system, 'w') as new_monitoring_system:
        new_monitoring_system.write(conf_monitoring_system)
