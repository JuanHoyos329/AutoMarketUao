CREATE SCHEMA IF NOT EXISTS AutoMarketUao  DEFAULT CHARACTER SET utf8 ;
USE AutoMarketUao ;
CREATE TABLE Publicaciones (
idPublicacion INT AUTO_INCREMENT NOT NULL,
userId INT NOT NULL,
  marca VARCHAR(45) NOT NULL,
  modelo VARCHAR(45) NOT NULL,
  año INT  NOT NULL,
  precio INT NOT NULL,
  kilometraje INT NOT NULL,
  tipo_combustible VARCHAR(45) NOT NULL,
  transmision varchar(45) NOT NULL,
  tamaño_motor FLOAT NOT NULL,
  puertas INT NOT NULL,
  ultimo_dueño VARCHAR(45) NOT NULL,
  descripcion VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(45) NOT NULL,
  estado ENUM ("Disponible","Vendido","Reservado") NOT NULL,
  primary key (idPublicacion)
  
  );
  
  


