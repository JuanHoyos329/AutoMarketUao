# 🚗 AutoMarketUAO

**AutoMarketUAO** es una plataforma web para la publicación, gestión y seguimiento de vehículos en procesos de compra y venta. Diseñada como proyecto académico, se basa en una arquitectura de microservicios y contenedores.

---

## 🛠️ Tecnologías utilizadas

* **Frontend:** JavaScript + PHP
* **Backend:** Spring Boot (Java), Node.js
* **Microservicios:** 3 en Node.js, 1 en Java
* **Base de datos:** MySQL (4 instancias independientes)
* **Contenedores:** Docker + Docker Swarm (Stack)
* **Infraestructura:** Vagrant + Apache2
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
git clone https://github.com/JuanHoyos329/AutoMarketUao.git
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

Lo que debemos hacer aquí es copiar las bases de datos y nuestros archivos CSV, ya que los contenedores que 
contienen las bases de datos y el clúster de Spark se encuentran en el worker. 
Por lo tanto, vamos a copiar estos archivos a sus respectivos contenedores y clúster. Para ello, realizamos lo siguiente:

1. Copia los archivos `.sql` y `.csv` a la carpeta compartida de Vagrant.
   Si no existe la carpeta `Databases`, créala:

   ```bash
   mkdir Databases
   cp Databases/* /vagrant/Databases/
   cp csvs/* /vagrant/Databases/
   ```

2. En la **VM worker**, copia los archivos a los contenedores de MySQL:

   ```bash
   docker cp /vagrant/Databases/backtramites_tramites.sql (contenedor_tramites o ID):/backtramites_tramites.sql
   docker cp /vagrant/Databases/usersautomarketuao_users.sql (contenedor_users o ID):/usersautomarketuao_users.sql
   docker cp /vagrant/Databases/automarketuao_publicaciones.sql (contenedor_publicaciones o ID):/automarketuao_publicaciones.sql
   ```

3. Accede a cada contenedor e importa las bases de datos:

   ```bash
   docker exec -it contenedor_tramites bash
   mysql -uroot -proot backtramites < /backtramites_tramites.sql
   #Aqui debemos de hacer una modificacion para que la aplicacion use la tabla correcta
   mysql -uroot -proot
   use backtramites;
   drop table Tramites;
   rename table tramites to Tramites;
   exit
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

En caso de no tener Spark en su maquina virtual vea le siguiente archivo para instalarlo correctamente.
[Cómo instalar PySpark](https://docs.google.com/document/d/16QaoHkk6zZ1VkHMYHJLdYDZ2wLfBupyi/edit?usp=sharing&ouid=102286551969155299986&rtpof=true&sd=true)

### 1. Crear el archivo de consultas

Crea un archivo llamado `consultas.py` dentro del directorio `app` de tu instalación de Spark (`labSpark`), y copia el siguiente código:

```bash
from pyspark.sql import SparkSession
from pyspark.sql.functions import col, avg, count, desc, datediff, lit, round as spark_round, when
from pyspark.sql import Row
import sys

# Crear sesión Spark
spark = SparkSession.builder \
        .appName("AutoMarket") \
        .master("spark://192.168.100.3:7077") \
        .getOrCreate()

# Leer archivos de entrada
tramites_df = spark.read.options(header='True', inferSchema='True').csv(sys.argv[1])
publicaciones_df = spark.read.format("csv") \
    .option("header", "true") \
    .option("inferSchema", "true") \
    .option("multiLine", "true") \
    .option("quote", "\"") \
    .option("escape", "\"") \
    .load(sys.argv[2])

# CONSULTA 1: Cantidad de trámites por estado
consulta1 = tramites_df.groupBy("estado") \
    .count() \
    .orderBy(desc("count")) \
    .withColumn("consulta", lit("1 - Trámites por estado")) \
    .selectExpr("estado as categoria", "cast(count as string) as valor", "consulta")

# CONSULTA 2: Paso donde más se cancelan trámites ----------
pasos = ["revision_doc", "cita", "contrato", "pago", "Traspaso", "entrega"]
cancelados_df = tramites_df.filter(col("estado") == "Cancelado")

paso_cancelados = [(paso, cancelados_df.filter(col(paso) == 0).count()) for paso in pasos]
paso_cancelados_df = spark.createDataFrame(paso_cancelados, ["categoria", "cantidad"]) \
    .orderBy(desc("cantidad")) \
    .withColumn("consulta", lit("2 - Paso donde más se cancelan trámites")) \
    .selectExpr("categoria", "cast(cantidad as string) as valor", "consulta")

# CONSULTA 3: Promedio de duración de trámites finalizados ----------
duracion_df = tramites_df.filter(col("fecha_fin").isNotNull()) \
    .withColumn("duracion_dias", datediff(col("fecha_fin"), col("fecha_inicio"))) \
    .agg(avg("duracion_dias").alias("valor")) \
    .withColumn("consulta", lit("3 - Promedio de duración de trámites")) \
    .withColumn("categoria", lit("Duración promedio")) \
    .selectExpr("categoria", "cast(round(valor, 2) as string) as valor", "consulta")

# CONSULTA 4: Porcentaje de estado de los trámites
total_tramites = tramites_df.count()
consulta4 = tramites_df.groupBy("estado") \
    .count() \
    .withColumn("porcentaje", (col("count") / total_tramites) * 100) \
    .orderBy(desc("porcentaje")) \
    .withColumn("consulta", lit("4 - Porcentaje de trámites por estado")) \
    .selectExpr("estado as categoria", "concat(cast(round(porcentaje, 2) as string), '%') as valor", "consulta")

# CONSULTA 5: Promedio días entre publicación y finalización
tramites_filtrados = tramites_df.filter(col("fecha_fin").isNotNull()).select("id_vehiculo", "fecha_fin")
publicaciones_join = publicaciones_df.select(col("idPublicacion").alias("id_vehiculo"), col("fecha_publicacion"))

join_df = tramites_filtrados.join(publicaciones_join, on="id_vehiculo", how="inner")
con_dias_df = join_df.withColumn("dias_transcurridos", datediff(col("fecha_fin"), col("fecha_publicacion")))
promedio_dias = con_dias_df.select(avg("dias_transcurridos").alias("valor")) \
    .withColumn("consulta", lit("5 - Promedio días publicación a finalización")) \
    .withColumn("categoria", lit("Promedio días")) \
    .selectExpr("categoria", "cast(round(valor, 2) as string) as valor", "consulta")

# CONSULTA 6: Conteo y porcentaje de marcas
total_publicaciones = publicaciones_df.count()
marcas_df = publicaciones_df.groupBy("marca") \
    .agg(count("*").alias("cantidad")) \
    .withColumn("porcentaje", spark_round((col("cantidad") / total_publicaciones) * 100, 2)) \
    .withColumn("consulta", lit("6 - Publicaciones por marca")) \
    .selectExpr("marca as categoria", "concat(cast(porcentaje as string), '%') as valor", "consulta")

# CONSULTA 7: Promedio precio por marca, modelo y año
precio_df = publicaciones_df.groupBy("marca", "modelo", "ano") \
    .agg(spark_round(avg("precio"), 2).alias("valor")) \
    .withColumn("consulta", lit("7 - Promedio precio por marca y año")) \
    .selectExpr("concat(marca, ' ', modelo, ' ', ano) as categoria", "cast(valor as string) as valor", "consulta")

# CONSULTA 8: Porcentaje por ubicación
ubicaciones_validas = [
    "Bucaramanga", "Pereira", "Cali", "Manizales", "Medellín",
    "Cúcuta", "Santa Marta", "Cartagena", "Barranquilla", "Bogotá"
]
publicaciones_con_ciudad = publicaciones_df.withColumn(
    "ciudad",
    when(col("ubicacion").isin(ubicaciones_validas), col("ubicacion"))
    .when(col("estado").isin(ubicaciones_validas), col("estado"))
)

filtrado_ciudad_df = publicaciones_con_ciudad.filter(col("ciudad").isNotNull())
total_ciudades = filtrado_ciudad_df.count()

ubicacion_df = filtrado_ciudad_df.groupBy("ciudad") \
    .agg(count("*").alias("cantidad")) \
    .withColumn("porcentaje", spark_round((col("cantidad") / total_ciudades) * 100, 2)) \
    .withColumn("consulta", lit("8 - Porcentaje por ciudad")) \
    .selectExpr("ciudad as categoria", "concat(cast(porcentaje as string), '%') as valor", "consulta")

#CONSULTA 9: Marcas que mas vendieron y que menos vendieron

# Filtrar los registros donde el estado sea 'Finalizado'
ventas_finalizadas_df = tramites_df.filter(col("estado") == "Finalizado")

# Agrupar por marca y contar cuántas veces aparece cada una
ventas_por_marca_df = ventas_finalizadas_df.groupBy("marca") \
    .agg(count("*").alias("valor")) \
    .withColumnRenamed("marca", "categoria") \
    .withColumn("consulta", lit("Ventas por marca"))

# UNIR TODOS LOS RESULTADOS
resultado_final = consulta1.unionByName(paso_cancelados_df) \
    .unionByName(duracion_df) \
    .unionByName(consulta4) \
    .unionByName(promedio_dias) \
    .unionByName(marcas_df) \
    .unionByName(precio_df) \
    .unionByName(ubicacion_df) \
    .unionByName(ventas_por_marca_df)

resultado_final.show(truncate=False)
# En caso de guardar los resultados en su maquina quite el comentario de la siguiente linea
# resultado_final.coalesce(1).write.mode("overwrite").option("header", "true").csv(sys.argv[3])

```
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
* Si no ejecutas como root, debes de cambiar la ruta de labSpark.

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
