# 🚗 AutoMarketUAO

**AutoMarketUAO** es una plataforma web para la publicación, gestión y seguimiento de vehículos en procesos de compra y venta. Diseñada como proyecto académico, se basa en una arquitectura de microservicios y contenedores.

---

## 🛠️ Tecnologías utilizadas

* **Frontend:** JavaScript + PHP
* **Backend:** Spring Boot (Java), Node.js
* **Microservicios:** 3 en Node.js, 1 en Java
* **Base de datos:** MySQL (4 instancias independientes)
* **Contenedores:** Docker + Docker Swarm (Stack)
* **Infraestructura:** Vagrant + Apache2 + Bind9
* **Control de versiones:** Git + GitHub

---

## ⚙️ Instalación y ejecución del proyecto

> 🖥️ Este proyecto se ejecuta en una infraestructura con **2 máquinas virtuales**:
>
> * **Máster**: aloja el Frontend y Backend principal.
> * **Worker**: aloja los contenedores con las bases de datos.

> 📍 El repositorio debe ser clonado en la máquina con IP `192.168.100.2`.

---

### 1. Clonar el repositorio y desplegar el stack

```bash
git clone git@github.com:JuanHoyos329/AutoMarketUao.git
cd AutoMarketUao
```

Inicializa Docker Swarm y despliega el stack:

```bash
docker swarm init
# Agrega el nodo worker con el token que se genera en la otra maquina(192.168.100.3)
docker stack deploy -c docker-stack.yml automarketuao
```

---

### 2. Restaurar bases de datos y archivos CSV

1. Copia los archivos `.sql` y `.csv` a la carpeta compartida de Vagrant.
   Si no existe la carpeta `Databases`, créala:

   ```bash
   mkdir Databases
   cp Databases/* /vagrant/Databases/
   ```

2. En la **VM worker**, copia los archivos a los contenedores de MySQL:

   ```bash
   docker cp /vagrant/Databases/backtramites_tramites.sql contenedor_tramites:/backtramites_tramites.sql
   docker cp /vagrant/Databases/usersautomarketuao_users.sql contenedor_users:/usersautomarketuao_users.sql
   docker cp /vagrant/Databases/automarketuao_publicaciones.sql contenedor_publicaciones:/automarketuao_publicaciones.sql
   ```

3. Accede a cada contenedor e importa las bases de datos:

   ```bash
   docker exec -it contenedor_tramites bash
   mysql -uroot -proot backtramites < /backtramites_tramites.sql
   #Aqui debemos de hacer una modificacion para que la aplicacion use la tabla correcta
   mysql -uroot -proot
   rename table tramites to Tramites;
   exit

   docker exec -it contenedor_users bash
   mysql -uroot -proot usersautomarketuao < /usersautomarketuao_users.sql
   exit

   docker exec -it contenedor_publicaciones bash
   mysql -uroot -pannie AutoMarketUao < /automarketuao_publicaciones.sql
   exit
   ```

---

## 🌐 Acceder a la plataforma

Abre tu navegador y dirígete a:

```
http://192.168.100.2:80
```

---

## 👤 Cuentas de prueba

### 🛠️ Administrador

* **Correo:** [juanandres8000@hotmail.com](mailto:juanandres8000@hotmail.com)
* **Contraseña:** `0423`

### 👥 Usuario

* **Correo:** [adriana.perez44@gmail.com](mailto:adriana.perez44@gmail.com)
* **Contraseña:** `123456`

---

## ⚡ Consultas opcionales en Apache Spark

Si deseas realizar análisis sobre los datos, puedes ejecutar el siguiente script con Apache Spark.

### 1. Crear el archivo de consultas

Crea un archivo llamado `consultas.py` dentro del directorio `apps` de tu instalación de Spark (`labSpark`), y copia el siguiente código:
📎 *(El código permanece igual, no lo repito aquí para no duplicar contenido innecesariamente. Puedo limpiarlo si lo deseas.)*

---

### 2. Ejecutar el script

Ubícate en la carpeta `bin` de tu Spark y ejecuta el script con:

```bash
cd spark-3.5.5-bin-hadoop3/bin/
./spark-submit --master spark://192.168.100.3:7077 /root/labSpark/app/consultas.py "/vagrant/Databases/backtramites.csv" "/vagrant/Databases/publicaciones.csv" "/root/labSpark/resultsConsultas"
```

📌 **Nota:**

* Verifica que las rutas de los archivos CSV sean correctas.
* Asegúrate de que la IP del master de Spark (`192.168.100.3`) sea correcta.
* Si no ejecutas como root, puede que necesites permisos adicionales.

---

### 3. Ver resultados

Si decidiste guardar los resultados en un CSV, puedes encontrarlos en:

```bash
cd /root/labSpark/resultsConsultas
```

---

## ✅ ¡Listo!

Has completado toda la configuración y ejecución de AutoMarketUAO.
¡Gracias por utilizar nuestra aplicación! 🚀
