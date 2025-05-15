# Usar la imagen oficial de MySQL como base
FROM mysql:8

# Copiar el archivo Tramites.sql al directorio adecuado para que se ejecute automáticamente en la inicialización
COPY Tramites.sql /docker-entrypoint-initdb.d/

# Establecer las variables de entorno necesarias para MySQL
ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=backtramites
