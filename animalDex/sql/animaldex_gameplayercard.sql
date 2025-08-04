CREATE DATABASE  IF NOT EXISTS `animaldex` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `animaldex`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: animaldex
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `gameplayercard`
--

DROP TABLE IF EXISTS `gameplayercard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gameplayercard` (
  `gamePlayerCard_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `gamePlayer_id` int(11) NOT NULL,
  `round_number` int(11) NOT NULL,
  `selected_attribute` varchar(20) NOT NULL,
  `is_winner_card` tinyint(1) NOT NULL,
  `is_played` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`gamePlayerCard_id`),
  KEY `card_id` (`card_id`),
  KEY `gamePlayer_id` (`gamePlayer_id`),
  CONSTRAINT `gameplayercard_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`),
  CONSTRAINT `gameplayercard_ibfk_2` FOREIGN KEY (`gamePlayer_id`) REFERENCES `gameplayer` (`gamePlayer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gameplayercard`
--

LOCK TABLES `gameplayercard` WRITE;
/*!40000 ALTER TABLE `gameplayercard` DISABLE KEYS */;
INSERT INTO `gameplayercard` VALUES (1,40,1,1,'strength',0,0),(2,17,1,2,'strength',0,0),(3,42,1,3,'strength',0,0),(4,10,1,4,'strength',1,0),(5,27,1,5,'strength',0,0),(6,28,1,6,'strength',0,0),(7,14,1,7,'strength',0,0),(8,8,1,8,'strength',0,0),(9,46,2,1,'',0,0),(10,33,2,2,'',0,0),(11,45,2,3,'',0,0),(12,31,2,4,'',0,0),(13,7,2,5,'',0,0),(14,38,2,6,'',1,0),(15,30,2,7,'',0,0),(16,18,2,8,'',0,0),(17,3,3,1,'strength',0,0),(18,35,3,2,'strength',0,0),(19,12,3,3,'strength',0,0),(20,39,3,4,'strength',0,0),(21,17,3,5,'strength',1,0),(22,7,3,6,'strength',0,0),(23,37,3,7,'strength',0,0),(24,52,3,8,'strength',0,0),(25,46,4,1,'',0,0),(26,19,4,2,'',0,0),(27,11,4,3,'',0,0),(28,16,4,4,'',0,0),(29,9,4,5,'',1,0),(30,36,4,6,'',0,0),(31,22,4,7,'',0,0),(32,56,4,8,'',0,0);
/*!40000 ALTER TABLE `gameplayercard` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-03 21:06:17
