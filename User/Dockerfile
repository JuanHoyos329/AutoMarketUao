
FROM node:18

WORKDIR ./src

COPY package*.json ./
RUN npm install

COPY . .

# Descargar el script wait-for-it.sh
RUN apt-get update && apt-get install -y curl
RUN curl -o /wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
    chmod +x /wait-for-it.sh

EXPOSE 8081

CMD ["bash", "/wait-for-it.sh", "db:3306", "--","node", "src/server.js"]
