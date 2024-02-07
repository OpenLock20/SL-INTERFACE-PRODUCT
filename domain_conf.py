import socket
import netifaces
import ipaddress

def obtener_informacion_red(interfaz_deseada):
    direcciones = netifaces.ifaddresses(interfaz_deseada)
    if netifaces.AF_INET in direcciones:
        ip_info = direcciones[netifaces.AF_INET][0]
        direccion_ip = ip_info['addr']
        return direccion_ip
    else:
        return None

def actualizar_hosts(ip_nueva):
    archivo_hosts = "/etc/hosts"
    necesita_actualizacion = False
    encontrada_safelock = False  # Indicador de si encontramos la entrada safelock.ol
    # Espacios requeridos entre la IP y safelock.ol
    espacios = " " * 5

    with open(archivo_hosts, 'r') as file:
        lineas = file.readlines()

    with open(archivo_hosts, 'w') as file:
        for linea in lineas:
            if "localhost" in linea:
                partes = linea.split()
                if partes[0] != ip_nueva:
                    necesita_actualizacion = True
                    partes[0] = ip_nueva
                    linea = " ".join(partes) + "\n"
            elif "safelock.ol" in linea:
                encontrada_safelock = True  # Marcamos que hemos encontrado safelock.ol
                partes = linea.split()
                if partes[0] != ip_nueva:
                    necesita_actualizacion = True
                    # Actualizar la línea para safelock.ol con 5 espacios
                    linea = ip_nueva + espacios + "safelock.ol\n"
            file.write(linea)

        # Si no se encontró safelock.ol, agregarlo al final
        if not encontrada_safelock:
            necesita_actualizacion = True
            file.write(ip_nueva + espacios + "safelock.ol\n")

    return necesita_actualizacion

# Especifica la interfaz deseada, por ejemplo, "eth0"
interfaz_deseada = "eth0"
ip_actual = obtener_informacion_red(interfaz_deseada)

if ip_actual and actualizar_hosts(ip_actual):
    print("El archivo /etc/hosts ha sido actualizado con la nueva dirección IP.")
else:
    print("No se requiere actualización o la interfaz especificada no está disponible.")
