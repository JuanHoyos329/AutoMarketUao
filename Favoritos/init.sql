CREATE DATABASE IF NOT EXISTS favoritosdb;

USE favoritosdb;

CREATE TABLE IF NOT EXISTS favoritos (
  id INT NOT NULL AUTO_INCREMENT,
  userId INT NOT NULL,
  idPublicacion INT NOT NULL,
  fecha_favorito TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  marca VARCHAR(100) DEFAULT NULL,
  modelo VARCHAR(100) DEFAULT NULL,
  ano INT DEFAULT NULL,
  precio DECIMAL(12,2) DEFAULT NULL,
  kilometraje INT DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY userId (userId, idPublicacion)
);

