-- Eliminar la base de datos si ya existe y crearla nuevamente
DROP DATABASE IF EXISTS AutoMarketUao;
CREATE DATABASE AutoMarketUao;
USE AutoMarketUao;

-- Tabla Cars
CREATE TABLE Cars (
    idCar INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    engine_size FLOAT NOT NULL,
    fuel_type VARCHAR(50) NOT NULL,
    mileage FLOAT NOT NULL,
    doors INT NOT NULL,
    owner_counts INT NOT NULL,
    price FLOAT NOT NULL,
    last_owner VARCHAR(100) NOT NULL,
    entry_date DATETIME NOT NULL,
    exit_date DATETIME NOT NULL
);

-- Tabla Users
CREATE TABLE Users (
    idUser INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    password varchar(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL
);

-- Tabla Buy
CREATE TABLE Buy (
    idBuy INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idUser INT NOT NULL,
    idCar INT NOT NULL
);

-- Tabla Sell
CREATE TABLE Sell (
    idSell INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idUser INT NOT NULL,
    idCar INT NOT NULL,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    engine_size FLOAT NOT NULL,
    fuel_type VARCHAR(50) NOT NULL,
    mileage FLOAT NOT NULL,
    doors INT NOT NULL,
    owner_counts INT NOT NULL,
    price FLOAT NOT NULL,
    last_owner VARCHAR(100) NOT NULL,
    entry_date DATETIME NOT NULL,
    exit_date DATETIME NOT NULLusersusers
);
