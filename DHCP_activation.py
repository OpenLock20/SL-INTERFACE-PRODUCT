import netifaces
import ipaddress

def obtener_informacion_red(interfaz_deseada):
    # Obtener información de direcciones de la interfaz deseada
    direcciones = netifaces.ifaddresses(interfaz_deseada)

    if netifaces.AF_INET in direcciones:
        # Obtener la dirección IP y la máscara de red
        ip_info = direcciones[netifaces.AF_INET][0]
        direccion_ip = ip_info['addr']
        mascara_red = ip_info['netmask']

        # Crear un objeto de red con la dirección IP y la máscara
        red = ipaddress.IPv4Network(f"{direccion_ip}/{mascara_red}", strict=False)

        # Almacenar información en variables globales
        global gateway, pool_init, pool_end
        gateway = netifaces.gateways()['default'][netifaces.AF_INET][0]
        pool_init = red.network_address + 11
        pool_end = red.broadcast_address - 1

# Especifica la interfaz deseada, por ejemplo, "eth0"
interfaz_deseada = "eth0"

# Llama a la función para obtener información
obtener_informacion_red(interfaz_deseada)

# Configuración del servidor DHCP
config_dhcp = f"""# Pi-hole: A black hole for Internet advertisements
# (c) 2017 Pi-hole, LLC (https://pi-hole.net)
# Network-wide ad blocking via your own hardware.
#
# Dnsmasq config for Pi-hole's FTLDNS
#
# This file is copyright under the latest version of the EUPL.
# Please see LICENSE file for your rights under this license.

###############################################################################
#      FILE AUTOMATICALLY POPULATED BY PI-HOLE INSTALL/UPDATE PROCEDURE.      #
# ANY CHANGES MADE TO THIS FILE AFTER INSTALL WILL BE LOST ON THE NEXT UPDATE #
#                                                                             #
#        IF YOU WISH TO CHANGE THE UPSTREAM SERVERS, CHANGE THEM IN:          #
#                      /etc/pihole/setupVars.conf                             #
#                                                                             #
#        ANY OTHER CHANGES SHOULD BE MADE IN A SEPARATE CONFIG FILE           #
#                    WITHIN /etc/dnsmasq.d/yourname.conf                      #
###############################################################################

addn-hosts=/etc/pihole/local.list
addn-hosts=/etc/pihole/custom.list


localise-queries


no-resolv

log-queries
log-facility=/var/log/pihole/pihole.log

log-async
cache-size=10000
server=8.8.8.8
server=8.8.4.4
domain-needed
expand-hosts
bogus-priv
local-service


dhcp-authoritative
dhcp-range={pool_init},{pool_end},24h
dhcp-option=option:router,{gateway}
dhcp-leasefile=/etc/pihole/dhcp.leases
"""

# Guardar la configuración en el archivo
with open('/etc/dnsmasq.d/01-pihole.conf', 'w') as archivo_pihole_conf:
    archivo_pihole_conf.write(config_dhcp)
