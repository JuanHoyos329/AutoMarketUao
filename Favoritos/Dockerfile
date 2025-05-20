# Usar imagen oficial de Node.js
FROM node:20

# Crear directorio de trabajo
WORKDIR /app

# Copiar archivos de dependencias
COPY package*.json ./

# Instalar dependencias
RUN npm install -g nodemon

# Copiar el resto del código fuente
COPY . .

# Exponer el puerto de la API
EXPOSE 3002

# Comando para iniciar la aplicación
CMD ["npm", "start"]
