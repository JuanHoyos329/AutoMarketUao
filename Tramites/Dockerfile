FROM node:18
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install  

COPY src/ ./src/
EXPOSE 8082 

CMD ["npm","run","start"]
