CREATE DATABASE  IF NOT EXISTS `beehappy` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `beehappy`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: beehappy
-- ------------------------------------------------------
-- Server version	5.6.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alert`
--

DROP TABLE IF EXISTS `alert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `threshold` double NOT NULL,
  `comparison` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_17FD46C1989D9B62` (`slug`),
  KEY `IDX_17FD46C1C54C8C93` (`type_id`),
  KEY `IDX_17FD46C17E3C61F9` (`owner_id`),
  CONSTRAINT `FK_17FD46C17E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_17FD46C1C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alert`
--

LOCK TABLES `alert` WRITE;
/*!40000 ALTER TABLE `alert` DISABLE KEYS */;
/*!40000 ALTER TABLE `alert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alert_hive`
--

DROP TABLE IF EXISTS `alert_hive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alert_hive` (
  `alert_id` int(11) NOT NULL,
  `hive_id` int(11) NOT NULL,
  PRIMARY KEY (`alert_id`,`hive_id`),
  KEY `IDX_53DD697C93035F72` (`alert_id`),
  KEY `IDX_53DD697CE9A48D12` (`hive_id`),
  CONSTRAINT `FK_53DD697C93035F72` FOREIGN KEY (`alert_id`) REFERENCES `alert` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_53DD697CE9A48D12` FOREIGN KEY (`hive_id`) REFERENCES `hive` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alert_hive`
--

LOCK TABLES `alert_hive` WRITE;
/*!40000 ALTER TABLE `alert_hive` DISABLE KEYS */;
/*!40000 ALTER TABLE `alert_hive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_translations`
--

DROP TABLE IF EXISTS `ext_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `object_class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_key` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lookup_unique_idx` (`locale`,`object_class`,`field`,`foreign_key`),
  KEY `translations_lookup_idx` (`locale`,`object_class`,`foreign_key`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_translations`
--

LOCK TABLES `ext_translations` WRITE;
/*!40000 ALTER TABLE `ext_translations` DISABLE KEYS */;
INSERT INTO `ext_translations` VALUES (7,'fr','CoreBundle\\Entity\\Type','name','6','Température intérieur'),(8,'fr','CoreBundle\\Entity\\Type','description','6','Température à l\'intérieur de la ruche'),(9,'fr','CoreBundle\\Entity\\Type','name','8','Température extérieur'),(10,'fr','CoreBundle\\Entity\\Type','description','8','Température à l\'extérieur de la ruche'),(11,'fr','CoreBundle\\Entity\\Type','name','9','Qualité de l\'air extérieur'),(12,'fr','CoreBundle\\Entity\\Type','description','9','Qualité de l\'air à l\'extérieur de la ruche'),(13,'fr','CoreBundle\\Entity\\Type','name','10','Humidité intérieur'),(14,'fr','CoreBundle\\Entity\\Type','description','10','Humidité intérieur de la ruche'),(15,'fr','CoreBundle\\Entity\\Type','name','11','Luminosité'),(16,'fr','CoreBundle\\Entity\\Type','description','11','Luminosité');
/*!40000 ALTER TABLE `ext_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_notification`
--

DROP TABLE IF EXISTS `history_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hive_id` int(11) DEFAULT NULL,
  `alert_id` int(11) DEFAULT NULL,
  `sendAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8BA48531E9A48D12` (`hive_id`),
  KEY `IDX_8BA4853193035F72` (`alert_id`),
  CONSTRAINT `FK_8BA4853193035F72` FOREIGN KEY (`alert_id`) REFERENCES `alert` (`id`),
  CONSTRAINT `FK_8BA48531E9A48D12` FOREIGN KEY (`hive_id`) REFERENCES `hive` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_notification`
--

LOCK TABLES `history_notification` WRITE;
/*!40000 ALTER TABLE `history_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hive`
--

DROP TABLE IF EXISTS `hive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT NULL,
  `api_key` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` decimal(12,10) DEFAULT NULL,
  `longitude` decimal(13,10) DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DC6DBBF8989D9B62` (`slug`),
  KEY `IDX_DC6DBBF87E3C61F9` (`owner_id`),
  CONSTRAINT `FK_DC6DBBF87E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hive`
--

LOCK TABLES `hive` WRITE;
/*!40000 ALTER TABLE `hive` DISABLE KEYS */;
/*!40000 ALTER TABLE `hive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `measure`
--

DROP TABLE IF EXISTS `measure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `hive_id` int(11) DEFAULT NULL,
  `value` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_80071925C54C8C93` (`type_id`),
  KEY `IDX_80071925E9A48D12` (`hive_id`),
  CONSTRAINT `FK_80071925C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_80071925E9A48D12` FOREIGN KEY (`hive_id`) REFERENCES `hive` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measure`
--

LOCK TABLES `measure` WRITE;
/*!40000 ALTER TABLE `measure` DISABLE KEYS */;
/*!40000 ALTER TABLE `measure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refresh_tokens`
--

DROP TABLE IF EXISTS `refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refresh_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refresh_token` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valid` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9BACE7E1C74F2195` (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refresh_tokens`
--

LOCK TABLES `refresh_tokens` WRITE;
/*!40000 ALTER TABLE `refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `min` double NOT NULL,
  `max` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8CDE5729989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type`
--

LOCK TABLES `type` WRITE;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` VALUES (6,'Inside temperature','temperature-inside','Température à l\'intérieur de la ruche',-10,120,'2016-06-27 12:30:03','2016-06-27 12:30:03'),(8,'Outside temperature','temperature-outside','Température à l\'extérieur de la ruche',-10,120,'2016-06-27 12:31:25','2016-06-27 12:31:25'),(9,'Quality of air (outside)','air-quality-outside','Qualité de l\'air à l\'extérieur de la ruche',0,1000,'2016-06-27 12:36:24','2016-06-27 12:36:24'),(10,'Humidity inside','humidity-inside','Humidité intérieur de la ruche',0,100,'2016-06-27 12:51:34','2016-06-27 12:51:34'),(11,'Brightness','brightness','Luminosité',0,1000.1,'2016-06-27 12:51:34','2016-06-27 12:51:34');
/*!40000 ALTER TABLE `type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','admin','admin@beehappy.fr','admin@beehappy.fr',1,'65s748r788w040o4s00k8skwg04wk0c','Q9wy4xn9dgMED0DGlDIhN1ZcXf+hC7/pR9WAuLppkA+1C0krWFBSlYXHg1DFSE2DV3xCEjwKEoEHXzIOz0Srmg==',NULL,0,0,NULL,'m00QSVZi4yhVZZtOZ0ZMCAOGAmu20kCQYcurWezSr5Q','2016-06-27 16:15:32','a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}',0,NULL,'577291dc657aa.jpg','0000-00-00 00:00:00','2016-06-28 17:03:56'),(2,'user1','user1','user1@beehappy.fr','user1@beehappy.fr',1,'sn936zxflc0w40w00c0wsg0kccowos','5bpEmFPXJNQsuTVySbZiOOU25EIndBAwNRDCbAL7LXDEQgG8VmR5/JHRzVcONua3jIQAT2MO1Dv+ZOVVy9Dw9g==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,'user2','user2','user2@beehappy.fr','user2@beehappy.fr',1,'cfftkktswpskokgkwwcskcks0s8gkss','5qkmR//YoBY/3WOOS5Cl/xm39Kf6DTDfff3ZQZyMjjkZL0hT0iZwTTLpt2FNmG2m8O8blwCIlcm+S05QwS972g==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,NULL,'2016-08-22 20:34:10','2016-08-22 20:34:10');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-22 21:08:27
