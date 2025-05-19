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

### 1. Preparar el entorno en la VM máster (`192.168.100.2`)

#### 📦 Instalar Bind9

```bash
apt-get update
apt-get install -y bind9
systemctl status bind9
systemctl stop ufw
```

Configurar `/etc/default/named`:

```bash
OPTIONS="-u bind -4"
```

Editar `/etc/bind/named.conf.options`:

```bash
forwarders {
    8.8.8.8;
    8.8.4.4;
};
dnssec-validation no;
```

Configurar la zona en `/etc/bind/named.conf.default-zones`:

```bash
zone "automarketuao.com" {
    type master;
    file "/etc/bind/db.automarketuao.com";
};
```

Crear el archivo `/etc/bind/db.automarketuao.com`:

```
$TTL 86400
@   IN  SOA servidor1.automarketuao.com. admin.automarketuao.com. (
        3         ; Serial
        604800    ; Refresh
        86400     ; Retry
        2419200   ; Expire
        86400 )   ; Negative Cache TTL
;
@   IN  NS  servidor1.automarketuao.com.
servidor1.automarketuao.com. IN A 192.168.100.2
servidor2.automarketuao.com. IN A 192.168.100.3
servidor3.automarketuao.com. IN A 192.168.100.102
www.automarketuao.com. IN CNAME servidor1.automarketuao.com.
```

Recargar Bind9:

```bash
systemctl reload bind9
```

---

#### 🌐 Configurar Apache2

```bash
apt-get install -y apache2
systemctl status apache2
```

Crear archivo de configuración del sitio:

```bash
cd /etc/apache2/sites-available/
vim automarket.conf
```

Contenido del archivo:

```apache
<VirtualHost *:80>
    ServerName www.automarketuao.com
    DocumentRoot /var/www/automarket
</VirtualHost>
```

Crear carpeta y activar sitio:

```bash
mkdir -p /var/www/automarket
a2ensite automarket.conf
systemctl reload apache2
```

---

### 2. Configurar DNS en la máquina anfitriona (Windows o Mac)

Para poder ingresar a `www.automarketuao.com`, es necesario que el sistema anfitrión use como servidor DNS la máquina máster (`192.168.100.2`).

#### 🪟 En Windows

1. `Win + R` → `ncpa.cpl`
2. Clic derecho sobre la red (VirtualBox o Vagrant) → **Propiedades**
3. Seleccionar: **Protocolo de Internet versión 4 (TCP/IPv4)** → **Propiedades**
4. Activar: **Usar las siguientes direcciones de servidor DNS**
5. Establecer DNS preferido: `192.168.100.2`
6. Aceptar y cerrar

#### 🍎 En macOS

1. Preferencias del sistema → **Red**
2. Seleccionar red activa (Ethernet/Wi-Fi)
3. Clic en **Avanzado** → pestaña **DNS**
4. Agregar: `192.168.100.2`
5. Aplicar cambios
6. (Opcional) Ejecutar en Terminal: `sudo dscacheutil -flushcache`

---

### 3. Clonar el repositorio y desplegar

```bash
git clone git@github.com:JuanHoyos329/AutoMarketUao.git
cd AutoMarketUao
cp /Frontend\ Linux/* /var/www/automarket/
```

Inicializar Swarm y desplegar stack:

```bash
docker swarm init
# Agregar el nodo worker con el token generado
docker stack deploy -c docker-stack.yml automarketuao
```

---

### 4. Subir y restaurar bases de datos

Copiar los archivos SQL a la carpeta compartida de Vagrant:

```bash
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
http://www.automarketuao.com
```

---

## 👤 Cuentas de prueba

### Administrador

* **Correo:** [juanandres8000@hotmail.com](mailto:juanandres8000@hotmail.com)
* **Contraseña:** `0423`

### Usuario normal

* **Correo:** [adriana.perez44@gmail.com](mailto:adriana.perez44@gmail.com)
* **Contraseña:** `123456`
