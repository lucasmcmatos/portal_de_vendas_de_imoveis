-- MariaDB dump 10.19  Distrib 10.6.16-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sgc
-- ------------------------------------------------------
-- Server version	10.6.16-MariaDB-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `news`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `title` varchar(100) DEFAULT NULL,
  `body` varchar(1000) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `summary` varchar(100) DEFAULT NULL,
  `schedule` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES ('teste','asdfasdfasdf','187a4ec80d0c522a075f9bd0b4fbe4d044743beece5917e297546611e280b74f',1,'2024-04-19 00:58:51',24,NULL,NULL),('naaso','<p>asdfasdfa</p><figure class=\"image\"><img style=\"aspect-ratio:1905/997;\" src=\"http://localhost/profile/223ef9ec5732b8af45db4a094c07f6fd28172d096fe11c789c26f48654de9cfe\" width=\"1905\" height=\"997\"></figure><blockquote><p>asdfas dfasjdfkaolsd fasd <strong>asdfasasdfasdfasdgasdg</strong>a fasdfasafsa</p></blockquote><p>aosdjfoaisjd ogaosdjf alskdjfasdljlgasld ga</p><p>afsjd lfamslga s<u>mdfamsdf</u></p>','cc2c9855eb022d1ba49851e2766eaa37fcd7760abf9e61ff08b93eff7c00f698',0,'2024-04-19 00:59:28',25,'asdfasdfasga',NULL),('sdfasdasdgasdf','<p>fasdarasdfasdfasdf ckeditor edit</p>','0de6ace0d143fa5b03e05478de8ec1141981d51d6b5d1542fdfcfc29b01198c5',1,'2024-04-19 01:36:44',26,'resumo',NULL),('teste','<figure class=\"image\"><img style=\"aspect-ratio:1910/921;\" src=\"http://localhost/profile/fa124d3b15f9abf939c5e14f60a0b79573221d0b7b2ea8a4a5f387b4c902473d\" width=\"1910\" height=\"921\"></figure><p>Aparentemente ta <strong>funcionando </strong>!!!</p>','',0,'2024-04-19 20:45:31',27,'teste file uploader',NULL),('asdfasdfasdfa','<p>asdfasdfasdf</p>','',1,'2024-04-21 23:19:16',28,'adfsdfasdf',NULL);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `fullname` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `cpf` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `privilege` int(10) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pass` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('Admin','Admin','62276749318','','18da59f54781a506',1,3,'$argon2id$v=19$m=65536,t=4,p=1$eDQ1ampHeXl5NkxLdzcydg$qZcHlPonMC7Y6Nq8/yqg3jYR0ZAn45sBjncZVPXdB5o',NULL),('Marcos Guilherme','Marcos','89050659020','marcos@gmail.com','149fde464eb1cf8b',0,42,'$argon2id$v=19$m=65536,t=4,p=1$ODVIbGllS3gxRWhPOG54WQ$7AaGETbTxHQVfx3rcT5DbhpWKjsany/kQnqtv2Zi0Ew',NULL),('Juvenal','Juvenalasdfadsfa','36738427069','juvenal@gmail.com','',0,45,'$argon2id$v=19$m=65536,t=4,p=1$VUhrRkVtdjI3d0ZseWVIUw$+US+GeH6rixBO440E5ps9byQgO52X2mFSU2f4f0/EM8',NULL),('teste','teste','96197739003','teste@gmail.com','undraw_profile.svg',0,46,'$argon2id$v=19$m=65536,t=4,p=1$Y0luOWU3dzVhQS9YY3g2dQ$dqRq7tv6B6B5RkiLqvHR9oo0WNqbgmT4SIWVrN6F5qU','2024-04-18');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sgc'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-24 17:22:18
