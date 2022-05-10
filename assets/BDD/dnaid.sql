-- MySQL dump 10.13  Distrib 8.0.28, for macos12.2 (arm64)
--
-- Host: localhost    Database: dnaid
-- ------------------------------------------------------
-- Server version	8.0.28

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
-- Table structure for table `appartenir`
--

DROP TABLE IF EXISTS `appartenir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appartenir` (
  `idappartenir` int NOT NULL AUTO_INCREMENT,
  `justeappartenir` int NOT NULL,
  `groupeappartenir` int NOT NULL,
  `roleappartenir` varchar(45) NOT NULL,
  `statutappartnir` int DEFAULT NULL COMMENT 'Indique si le juste a quitté le groupe ou non',
  `datedebutappartenir` date NOT NULL,
  `datefinappartenir` date DEFAULT NULL,
  `descriptionappartenir` text,
  PRIMARY KEY (`idappartenir`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assemblee`
--

DROP TABLE IF EXISTS `assemblee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assemblee` (
  `idassemble` int NOT NULL AUTO_INCREMENT,
  `matassemble` varchar(7) NOT NULL,
  `nomassemble` varchar(45) NOT NULL,
  `paysassemble` varchar(45) NOT NULL,
  `regionassemble` varchar(45) DEFAULT NULL,
  `departassemble` varchar(45) DEFAULT NULL,
  `villeassembe` varchar(45) NOT NULL,
  `communeassemble` varchar(45) DEFAULT NULL,
  `quartierassemble` varchar(45) DEFAULT NULL,
  `fulltextassemble` text,
  PRIMARY KEY (`idassemble`),
  FULLTEXT KEY `FULLTEXT` (`fulltextassemble`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe` (
  `idgroupe` int NOT NULL AUTO_INCREMENT,
  `matgroupe` varchar(7) NOT NULL,
  `nomgroupe` varchar(45) NOT NULL,
  `datecreatgroupe` date DEFAULT NULL,
  `idservicegroupe` int NOT NULL,
  PRIMARY KEY (`idgroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `juste`
--

DROP TABLE IF EXISTS `juste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `juste` (
  `idjuste` int NOT NULL AUTO_INCREMENT,
  `nomjuste` varchar(45) NOT NULL,
  `prenomjuste` varchar(150) NOT NULL,
  `surnomjuste` varchar(45) DEFAULT NULL,
  `datenaissjuste` date DEFAULT NULL,
  `genrejuste` int NOT NULL,
  `etatjuste` int NOT NULL DEFAULT '1' COMMENT 'Vivant ou décédé',
  `adressejuste` varchar(45) DEFAULT NULL,
  `phonejuste` varchar(45) DEFAULT NULL,
  `gradejuste` varchar(45) NOT NULL,
  `anneenvelnaissjuste` year DEFAULT NULL,
  `professionjuste` varchar(45) DEFAULT NULL,
  `statutmatrijuste` varchar(45) DEFAULT NULL,
  `ethniejuste` varchar(45) DEFAULT NULL,
  `photojuste` varchar(45) DEFAULT NULL,
  `fulltextjuste` text,
  PRIMARY KEY (`idjuste`),
  FULLTEXT KEY `FULLTEXT` (`fulltextjuste`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rattacher`
--

DROP TABLE IF EXISTS `rattacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rattacher` (
  `idrattacher` int NOT NULL AUTO_INCREMENT,
  `justerattacher` int NOT NULL,
  `assemblerattacher` int NOT NULL,
  `fonctionrattacher` varchar(45) NOT NULL,
  `statutjusterattacher` int DEFAULT NULL COMMENT 'Indique si le juste à changer d’assemble ou à été excommunié\\nEn cas d’excommuniation la date de fin marque la date d’excommuniation\\nUn juste peut être aussi suspendu avant son excommunication',
  `datedebutrattacher` date NOT NULL,
  `datefinrattacher` date DEFAULT NULL,
  `descriptionrattacher` text COMMENT 'Enregistre les raisons de fin de rattachement\nOn enregistre les détails marquant la fin du rattachement en cas d’excommunication on donnera les raisons.',
  PRIMARY KEY (`idrattacher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `idservice` int NOT NULL AUTO_INCREMENT,
  `matservice` varchar(6) NOT NULL,
  `nomservice` varchar(45) NOT NULL,
  `datecreatservice` date DEFAULT NULL,
  PRIMARY KEY (`idservice`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-10  7:58:43
