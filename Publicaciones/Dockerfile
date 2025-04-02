# Usamos la imagen de OpenJDK para Java 17
FROM openjdk:21-jdk-slim

# Definimos el directorio de trabajo en el contenedor
WORKDIR /app

# Copiamos el archivo JAR generado por Maven
COPY target/*.jar app.jar  

# Exponer el puerto en el que corre la aplicación
EXPOSE 8080  

# Comando para ejecutar la aplicación
CMD ["mvn spring-boot:run"]
