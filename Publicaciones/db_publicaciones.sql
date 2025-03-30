CREATE SCHEMA IF NOT EXISTS AutoMarketUao  DEFAULT CHARACTER SET utf8 ;
USE AutoMarketUao ;
CREATE TABLE Publicaciones (
idPublicacion INT AUTO_INCREMENT NOT NULL,
userId INT NOT NULL,
  marca VARCHAR(45) NOT NULL,
  modelo VARCHAR(45) NOT NULL,
  ano INT  NOT NULL,
  precio INT NOT NULL,
  kilometraje INT NOT NULL,
  tipo_combustible VARCHAR(45) NOT NULL,
  transmision varchar(45) NOT NULL,
  tamano_motor FLOAT NOT NULL,
  puertas INT NOT NULL,
  ultimo_dueno VARCHAR(45) NOT NULL,
  descripcion VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(45) NOT NULL,
  estado ENUM ("Disponible","Vendido","Reservado") NOT NULL,
  primary key (idPublicacion)
  
  );
  
  


