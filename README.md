# AutoMarketUAO 🚗

**AutoMarketUAO** es una plataforma web para la publicación, gestión y seguimiento de vehículos en compra y venta, diseñada como proyecto académico con una arquitectura basada en microservicios y contenedores.

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

> 🔧 El repositorio se debe clonar en la máquina con IP `192.168.100.2`. Se utilizan 2 máquinas virtuales: una actúa como **máster** (Frontend + Backend principal) y la otra como **worker** (contenedores de las bases de datos).

---

### 1. Clonar el repositorio y desplegar

```bash
git clone git@github.com:JuanHoyos329/AutoMarketUao.git
cd AutoMarketUao
```

Inicializar Swarm y desplegar stack:

```bash
docker swarm init
# Agregar el nodo worker con el token generado
docker stack deploy -c docker-stack.yml automarketuao
```

---

### 2. Subir y restaurar bases de datos

Copiar los archivos SQL a la carpeta compartida de Vagrant.
Aqui nosotros creamos un directorio en vagrant llamado Databases, en caso de no tenerlo creele usando:
```bash
mkdir Databases
cp Databases/* /vagrant/Databases/
```

En la **VM worker**, copiar los archivos a los contenedores MySQL:

```bash
docker cp /vagrant/Databases/backtramites_tramites.sql contenedor_tramites:/backtramites_tramites.sql
docker cp /vagrant/Databases/usersautomarketuao_users.sql contenedor_users:/usersautomarketuao_users.sql
docker cp /vagrant/Databases/automarketuao_publicaciones.sql contenedor_publicaciones:/automarketuao_publicaciones.sql
```

Ingresar a cada contenedor e importar la base de datos:

```bash
docker exec -it contenedor_tramites bash
mysql -uroot -proot backtramites < /backtramites_tramites.sql
exit

docker exec -it contenedor_users bash
mysql -uroot -proot usersautomarketuao < /usersautomarketuao_users.sql
exit

docker exec -it contenedor_publicaciones bash
mysql -uroot -pannie AutoMarketUao < /automarketuao_publicaciones.sql
exit
```

---

## 🚀 Acceder a la plataforma

Abre tu navegador y accede a:

```
http://192.168.100.2:80
```

---

## 👤 Cuentas de prueba

### Administrador

* **Correo:** [juanandres8000@hotmail.com](mailto:juanandres8000@hotmail.com)
* **Contraseña:** `0423`

### Usuario normal

* **Correo:** [adriana.perez44@gmail.com](mailto:adriana.perez44@gmail.com)
* **Contraseña:** `123456`
