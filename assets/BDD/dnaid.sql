-- MySQL dump 10.13  Distrib 8.0.28, for macos11 (x86_64)
--
-- Host: localhost    Database: dnaid
-- ------------------------------------------------------
-- Server version	8.0.28

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
-- Table structure for table `appartenir`
--

DROP TABLE IF EXISTS `appartenir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appartenir` (
  `idappartenir` bigint NOT NULL AUTO_INCREMENT,
  `justeappartenir` bigint NOT NULL,
  `groupeappartenir` bigint NOT NULL,
  `roleappartenir` varchar(45) NOT NULL,
  `statutappartnir` int DEFAULT NULL COMMENT 'Indique si le juste a quitté le groupe ou non',
  `datedebutappartenir` date NOT NULL,
  `datefinappartenir` date DEFAULT NULL,
  `descriptionappartenir` text,
  PRIMARY KEY (`idappartenir`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appartenir`
--

LOCK TABLES `appartenir` WRITE;
/*!40000 ALTER TABLE `appartenir` DISABLE KEYS */;
INSERT INTO `appartenir` VALUES (1,1,2,'Developpeur d\'application',1,'2022-02-25',NULL,NULL);
/*!40000 ALTER TABLE `appartenir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assemblee`
--

DROP TABLE IF EXISTS `assemblee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assemblee` (
  `idassemble` bigint NOT NULL AUTO_INCREMENT,
  `matassemble` varchar(10) NOT NULL,
  `nomassemble` varchar(45) NOT NULL,
  `paysassemble` varchar(45) NOT NULL,
  `regionassemble` varchar(45) DEFAULT NULL,
  `departassemble` varchar(45) DEFAULT NULL,
  `villeassemble` varchar(45) NOT NULL,
  `communeassemble` varchar(45) DEFAULT NULL,
  `quartierassemble` varchar(45) DEFAULT NULL,
  `descriptionassemblee` text,
  `fulltextassemble` text,
  PRIMARY KEY (`idassemble`),
  FULLTEXT KEY `FULLTEXT` (`fulltextassemble`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assemblee`
--

LOCK TABLES `assemblee` WRITE;
/*!40000 ALTER TABLE `assemblee` DISABLE KEYS */;
INSERT INTO `assemblee` VALUES (1,'A-8885E1','Base de niangon nord','Cote d\'ivoire','Lagunes','Lagunes','Abidjan','Yopougon','Niangon nord','Eglise mère','A-8885E1 Base de niangon nord Cote d\'ivoire Lagunes Lagunes Abidjan Yopougon Niangon nord Eglise mère');
/*!40000 ALTER TABLE `assemblee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe` (
  `idgroupe` bigint NOT NULL AUTO_INCREMENT,
  `matgroupe` varchar(10) NOT NULL,
  `nomgroupe` varchar(45) NOT NULL,
  `datecreatgroupe` date DEFAULT NULL,
  `descriptiongroupe` text,
  `idservicegroupe` bigint NOT NULL,
  PRIMARY KEY (`idgroupe`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe`
--

LOCK TABLES `groupe` WRITE;
/*!40000 ALTER TABLE `groupe` DISABLE KEYS */;
INSERT INTO `groupe` VALUES (1,'G-4F296D','Divine tech','2022-02-12',NULL,1),(2,'G-61DAD5','Developpement informatique','2022-03-03',NULL,1);
/*!40000 ALTER TABLE `groupe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juste`
--

DROP TABLE IF EXISTS `juste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `juste` (
  `idjuste` bigint NOT NULL AUTO_INCREMENT,
  `nomjuste` varchar(45) NOT NULL,
  `prenomjuste` varchar(150) NOT NULL,
  `surnomjuste` varchar(45) DEFAULT NULL,
  `datenaissjuste` date DEFAULT NULL,
  `genrejuste` varchar(10) NOT NULL,
  `etatjuste` varchar(20) NOT NULL COMMENT 'Vivant ou décédé',
  `adressejuste` varchar(45) DEFAULT NULL,
  `phonejuste` varchar(45) DEFAULT NULL,
  `gradejuste` varchar(45) NOT NULL,
  `anneenvelnaissjuste` year DEFAULT NULL,
  `professionjuste` varchar(45) DEFAULT NULL,
  `statutmatrijuste` varchar(45) DEFAULT NULL,
  `ethniejuste` varchar(45) DEFAULT NULL,
  `photojuste` varchar(45) DEFAULT NULL,
  `fulltextjuste` text,
  `niveaujuste` int DEFAULT '0' COMMENT 'Indique si le juste est lambda, administrateur ou super administrateur ',
  `loginjuste` varchar(45) DEFAULT NULL,
  `mdpjuste` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idjuste`),
  FULLTEXT KEY `FULLTEXT` (`fulltextjuste`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juste`
--

LOCK TABLES `juste` WRITE;
/*!40000 ALTER TABLE `juste` DISABLE KEYS */;
INSERT INTO `juste` VALUES (1,'Yavo','Kouaho Roland Isidore','Diesel Suprême','1992-02-12','Homme','Vivant','Abidjan Yopougon Niangon Cité Caféier','0759169534','Laic',2018,'Informaticien','Célibataire','Abbey','','Yavo Kouaho Roland Isidore Diesel Suprême 0759169534 Homme Abidjan Yopougon Niangon Cité Caféier Laic Informaticien Célibataire Abbey A-8327B4 Base de niangon nord Cote d\'ivoire Lagunes Lagunes Abidjan Yopougon Niangon nord Eglise mère',2,'J-76D27C','$2y$10$OHVP2.S/aM/b5fuEVPnWqe9HtiP3JoSWxT2t0gViR0Q.dGYfVVi56'),(14,'Admin','Super Admin','Le premier','1992-02-12','Homme','Vivant','Omnipresent','0000000000','fort',1999,'Administrateur','Célibataire','Toute les lanques','','Admin Super Admin Le premier Homme Omnipresent fort Administrateur Célibataire Toute les lanques',2,'J-201ADC','$2y$10$zDxfkJeoBwuilJ0Wln1mdeFOl4k4kVKFhps7kbQQaqFgR9E/DFeI6'),(15,'Konan','Yves neymard','Le pro','1995-04-12','Homme','Vivant','Niangon sud à gauche','0506687474','Laic',2019,'Infirmier','Célibataire','Béthé','','Konan Yves neymard Le pro 0506687474 Homme Niangon sud à gauche Laic Infirmier Célibataire Béthé',2,'J-76D27C','$2y$10$OHVP2.S/aM/b5fuEVPnWqe9HtiP3JoSWxT2t0gViR0Q.dGYfVVi56');
/*!40000 ALTER TABLE `juste` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rattacher`
--

DROP TABLE IF EXISTS `rattacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rattacher` (
  `idrattacher` bigint NOT NULL AUTO_INCREMENT,
  `justerattacher` bigint NOT NULL,
  `assemblerattacher` bigint NOT NULL,
  `fonctionrattacher` varchar(45) NOT NULL,
  `statutrattacher` int DEFAULT NULL COMMENT 'Indique si le juste à changer d’assemble ou à été excommunié\\\\nEn cas d’excommuniation la date de fin marque la date d’excommuniation\\\\nUn juste peut être aussi suspendu avant son excommunication',
  `datedebutrattacher` date NOT NULL,
  `datefinrattacher` date DEFAULT NULL,
  `descriptionrattacher` text COMMENT 'Enregistre les raisons de fin de rattachement\nOn enregistre les détails marquant la fin du rattachement en cas d’excommunication on donnera les raisons.',
  PRIMARY KEY (`idrattacher`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rattacher`
--

LOCK TABLES `rattacher` WRITE;
/*!40000 ALTER TABLE `rattacher` DISABLE KEYS */;
INSERT INTO `rattacher` VALUES (1,1,1,'leader',1,'2018-08-12',NULL,NULL);
/*!40000 ALTER TABLE `rattacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `idservice` bigint NOT NULL AUTO_INCREMENT,
  `matservice` varchar(10) NOT NULL,
  `nomservice` varchar(45) NOT NULL,
  `datecreatservice` date DEFAULT NULL,
  PRIMARY KEY (`idservice`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (1,'S-6C7C5D','DNAID','2016-08-06');
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-01 10:06:10
