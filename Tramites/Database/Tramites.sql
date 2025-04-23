-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS backtramites 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE backtramites;

-- Crear la tabla tramites
CREATE TABLE IF NOT EXISTS Tramites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_vendedor INT(11) NOT NULL,
    user_vendedor VARCHAR(30) NOT NULL,
    tel_vendedor VARCHAR(20) NULL,
    email_vendedor VARCHAR(30) NOT NULL,
    id_comprador INT(11) NOT NULL,
    user_comprador VARCHAR(30) NOT NULL,
    tel_comprador VARCHAR(20) NULL,
    email_comprador VARCHAR(30) NOT NULL,
    id_vehiculo INT(11) NOT NULL,
    marca VARCHAR(30) NOT NULL,
    modelo VARCHAR(30) NOT NULL,
    ano INT(11) NOT NULL,
    precio INT(11) NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    revision_doc TINYINT(1) NOT NULL,
    cita TINYINT(1) NOT NULL,
    contrato TINYINT(1) NOT NULL,
    pago TINYINT(1) NOT NULL,
    Traspaso TINYINT(1) NOT NULL,
    entrega TINYINT(1) NOT NULL,
    fecha_fin DATETIME NULL,
    estado VARCHAR(10) NOT NULL
    
)CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

