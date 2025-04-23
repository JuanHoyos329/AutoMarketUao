# Dockerfile
FROM node:18

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .

# Descargar el script wait-for-it.sh
RUN apt-get update && apt-get install -y curl
RUN curl -o /wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
    chmod +x /wait-for-it.sh

EXPOSE 8082

# Ejecutar el script como shell, no como módulo Node.js
CMD ["bash", "/wait-for-it.sh", "db:3306", "--", "npm", "run", "start"]
