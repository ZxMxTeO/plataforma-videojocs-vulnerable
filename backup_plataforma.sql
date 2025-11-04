-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: plataforma_videojocs
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.24.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `jocs`
--

DROP TABLE IF EXISTS `jocs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jocs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_joc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcio` text COLLATE utf8mb4_unicode_ci,
  `puntuacio_maxima` int DEFAULT '0',
  `nivells_totals` int DEFAULT '1',
  `actiu` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jocs`
--

LOCK TABLES `jocs` WRITE;
/*!40000 ALTER TABLE `jocs` DISABLE KEYS */;
INSERT INTO `jocs` VALUES (1,'Nave Espacial','Juego de disparos espaciales',0,3,1),(2,'Joc_2','Creado automaticamente para 2',0,10,1);
/*!40000 ALTER TABLE `jocs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nivells_joc`
--

DROP TABLE IF EXISTS `nivells_joc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nivells_joc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `joc_id` int NOT NULL,
  `nivell` int NOT NULL,
  `nom_nivell` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuracio_json` json NOT NULL,
  `puntuacio_minima` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `joc_id` (`joc_id`),
  CONSTRAINT `nivells_joc_ibfk_1` FOREIGN KEY (`joc_id`) REFERENCES `jocs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nivells_joc`
--

LOCK TABLES `nivells_joc` WRITE;
/*!40000 ALTER TABLE `nivells_joc` DISABLE KEYS */;
INSERT INTO `nivells_joc` VALUES (5,1,1,'Nivel 1','{\"vides\": 3, \"enemics\": 5, \"velocitat\": 5, \"puntuacio_final\": 100}',100),(6,1,2,'Nivel 2','{\"vides\": 5, \"enemics\": 15, \"velocitat\": 10, \"puntuacio_final\": 150}',200),(7,1,3,'Nivel 3','{\"vides\": 8, \"enemics\": 30, \"velocitat\": 15, \"puntuacio_final\": 200}',300);
/*!40000 ALTER TABLE `nivells_joc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partides`
--

DROP TABLE IF EXISTS `partides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuari_id` int NOT NULL,
  `joc_id` int NOT NULL,
  `nivell_jugat` int NOT NULL,
  `puntuacio_obtinguda` int NOT NULL,
  `data_partida` datetime DEFAULT CURRENT_TIMESTAMP,
  `durada_segons` int DEFAULT '0',
  `vidas` int DEFAULT '3',
  PRIMARY KEY (`id`),
  KEY `usuari_id` (`usuari_id`),
  KEY `joc_id` (`joc_id`),
  CONSTRAINT `partides_ibfk_1` FOREIGN KEY (`usuari_id`) REFERENCES `usuaris` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partides_ibfk_2` FOREIGN KEY (`joc_id`) REFERENCES `jocs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partides`
--

LOCK TABLES `partides` WRITE;
/*!40000 ALTER TABLE `partides` DISABLE KEYS */;
INSERT INTO `partides` VALUES (1,5,2,6,27,'2025-11-04 15:22:26',0,0),(2,3,2,7,30,'2025-11-04 15:30:57',0,0),(3,3,2,7,30,'2025-11-04 15:55:40',0,0),(4,3,2,6,27,'2025-11-04 15:56:05',0,0);
/*!40000 ALTER TABLE `partides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progres_usuari`
--

DROP TABLE IF EXISTS `progres_usuari`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progres_usuari` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuari_id` int NOT NULL,
  `joc_id` int NOT NULL,
  `nivell_actual` int DEFAULT '1',
  `puntuacio_maxima` int DEFAULT '0',
  `partides_jugades` int DEFAULT '0',
  `ultima_partida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuari_id` (`usuari_id`),
  KEY `joc_id` (`joc_id`),
  CONSTRAINT `progres_usuari_ibfk_1` FOREIGN KEY (`usuari_id`) REFERENCES `usuaris` (`id`) ON DELETE CASCADE,
  CONSTRAINT `progres_usuari_ibfk_2` FOREIGN KEY (`joc_id`) REFERENCES `jocs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progres_usuari`
--

LOCK TABLES `progres_usuari` WRITE;
/*!40000 ALTER TABLE `progres_usuari` DISABLE KEYS */;
INSERT INTO `progres_usuari` VALUES (1,5,1,1,0,1,'2025-11-04 15:44:45'),(2,5,2,1,0,1,'2025-11-04 15:39:49'),(3,3,1,2,100,1,'2025-11-04 15:43:09'),(4,3,2,1,80,1,'2025-11-04 15:42:43');
/*!40000 ALTER TABLE `progres_usuari` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuaris`
--

DROP TABLE IF EXISTS `usuaris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuaris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_usuari` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_complet` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_registre` datetime DEFAULT CURRENT_TIMESTAMP,
  `imatge_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_usuari` (`nom_usuari`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuaris`
--

LOCK TABLES `usuaris` WRITE;
/*!40000 ALTER TABLE `usuaris` DISABLE KEYS */;
INSERT INTO `usuaris` VALUES (1,'','','','','2025-10-17 14:27:53',NULL),(3,'Marc','pichuladetoro@gmail.com','membrillo69','Marc Pimentel Pichabrava','2025-10-17 14:32:18','uploads/gengar.png'),(5,'Mateo','mateouwu@gmail.com','ubu','Mateo Luna Pichulagorda','2025-10-17 14:41:00','uploads/messi.webp');
/*!40000 ALTER TABLE `usuaris` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-04 17:54:13
