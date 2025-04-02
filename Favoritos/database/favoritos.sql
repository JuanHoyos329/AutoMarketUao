CREATE DATABASE favoritos;
USE favoritos;
CREATE TABLE `favoritos` (
    `id` int primary key auto_increment,
    `userId` int NOT NULL,
    `idPublicacion` int NOT NULL,
    `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;