-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: usersautomarketuao
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `userId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`email`),
  UNIQUE KEY `userId` (`userId`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (64,'Adriana','Perez','adrianaperez44','123456','adriana.perez44@gmail.com','3003456789','user'),(39,'Ana','López','analopez321','123456','ana.lopez32@outlook.com','3007890123','user'),(56,'Andrés','Montoya','andresmontoya555','123456','andres.montoya55@gmail.com','3125678901','user'),(44,'Andrés','Torres','andrestorres888','123456','andres.torres88@gmail.com','3143456789','user'),(46,'Camilo','Ortiz','camiloortiz444','123456','camilo.ortiz44@gmail.com','3225678901','user'),(38,'Carlos','Fernández','carlosfernandez456','123456','carlos.fernandez45@yahoo.com','3206543210','user'),(70,'Catalina','Jiménez','catalinajimenez111','123456','catalina.jimenez11@yahoo.com','3109012345','user'),(47,'Daniela','Mejía','danielamejia555','123456','daniela.mejia55@yahoo.com','3176789012','user'),(43,'Diana','Ramírez','dianaramirez777','123456','diana.ramirez77@outlook.com','3012345678','user'),(58,'Diego','Pineda','diegopineda777','123456','diego.pineda77@yahoo.com','3047890123','user'),(65,'Eduardo','Bermúdez','eduardobermudez555','123456','eduardo.bermudez55@hotmail.com','3154567890','user'),(59,'Elena','Ospina','elenaospina888','123456','elena.ospina88@outlook.com','3168901234','user'),(50,'Esteban','Mendoza','estebanmendoza888','123456','esteban.mendoza88@hotmail.com','3049012345','user'),(67,'Felipe','García','felipegarcia777','123456','felipe.garcia77@outlook.com','3046789012','user'),(49,'Fernanda','Ramírez','fernandaramirez777','123456','fernanda.ramirez77@gmail.com','3168901234','user'),(71,'Germán','Patiño','germanpatino222','123456','german.patino22@outlook.com','3000123456','user'),(61,'Gloria','Díaz','gloriadiaz111','123456','gloria.diaz11@hotmail.com','3230123456','user'),(62,'Héctor','Cárdenas','hectorcardenas222','123456','hector.cardenas22@yahoo.com','3171234567','user'),(63,'Javier','Quintero','javierquintero333','123456','javier.quintero33@outlook.com','3102345678','user'),(36,'Juan','Gómez','juangomez123','123456','juan.gomez12@gmail.com','3101234567','user'),(14,'Juan','Hoyos','Juan0423','0423','juanandres8000@hotmail.com','3164621880','admin'),(53,'Laura','Ríos','laurarios222','123456','laura.rios22@hotmail.com','3102345678','user'),(72,'Lorena','Rojas','lorenarojas333','123456','lorena.rojas33@gmail.com','3201234567','user'),(40,'Luis','Martínez','luismartinez999','123456','luis.martinez99@gmail.com','3054567890','user'),(57,'Margarita','Jiménez','margaritajimenez666','123456','margarita.jimenez66@hotmail.com','3186789012','user'),(37,'María','Rodríguez','mariarodriguez89','123456','maria.rodriguez89@hotmail.com','3159876543','user'),(48,'Mateo','Suárez','mateosuarez666','123456','mateo.suarez66@outlook.com','3137890123','user'),(69,'Mauricio','Navarro','mauricionavarro999','123456','mauricio.navarro99@hotmail.com','3168901234','user'),(74,'Nathalie','Cruz','nathaliecruz555','123456','nathalie.cruz55@yahoo.com','3173456789','user'),(52,'Oscar','Giraldo','oscargiraldo111','123456','oscar.giraldo11@gmail.com','3001234567','user'),(55,'Paola','Hernández','paolahernandez444','123456','paola.hernandez44@outlook.com','3154567890','user'),(42,'Pedro','Gutiérrez','pedrogutierrez222','123456','pedro.gutierrez22@yahoo.com','3186781234','user'),(54,'Ricardo','Vega','ricardovega333','123456','ricardo.vega33@yahoo.com','3203456789','user'),(73,'Roberto','Muñoz','robertomunoz444','123456','roberto.munoz44@hotmail.com','3182345678','user'),(60,'Samuel','López','samuellopez999','123456','samuel.lopez99@gmail.com','3139012345','user'),(68,'Sandra','Vargas','sandravargas888','123456','sandra.vargas88@gmail.com','3137890123','user'),(41,'Sofía','Pérez','sofiaperez555','123456','sofia.perez55@hotmail.com','3121237894','user'),(51,'Tatiana','Castaño','tatianacastano999','123456','tatiana.castano99@yahoo.com','3230123456','user'),(45,'Valeria','Vargas','valeriavargas333','123456','valeria.vargas33@hotmail.com','3194567891','user'),(66,'Vanessa','Mora','vanessamora666','123456','vanessa.mora66@yahoo.com','3225678901','user');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-10 13:35:53
