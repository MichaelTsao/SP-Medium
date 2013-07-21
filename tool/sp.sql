-- MySQL dump 10.13  Distrib 5.1.50, for pc-linux-gnu (i686)
--
-- Host: localhost    Database: sp
-- ------------------------------------------------------
-- Server version	5.1.50-log

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
-- Table structure for table `adjust`
--

DROP TABLE IF EXISTS `adjust`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adjust` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `platform` tinyint(4) DEFAULT NULL,
  `percent` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `client` (`client`),
  KEY `t` (`time`),
  KEY `pf` (`platform`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `command`
--

DROP TABLE IF EXISTS `command`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `command` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `spid` int(11) DEFAULT NULL,
  `cmd_type` int(1) DEFAULT NULL,
  `prov` int(2) DEFAULT NULL,
  `operator` int(1) DEFAULT NULL,
  `content` char(255) DEFAULT NULL,
  `content_type` int(1) DEFAULT NULL,
  `port` char(50) DEFAULT NULL,
  `long_code` char(50) DEFAULT NULL,
  `platform` int(1) DEFAULT NULL,
  `fee` int(11) DEFAULT NULL,
  `fee_type` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spid` (`spid`),
  KEY `ctype` (`cmd_type`),
  KEY `op` (`operator`),
  KEY `prov` (`prov`),
  KEY `contype` (`content_type`),
  KEY `platform` (`platform`),
  KEY `fee_type` (`fee_type`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `command_area`
--

DROP TABLE IF EXISTS `command_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `command_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commandid` int(11) DEFAULT NULL,
  `prov` int(2) DEFAULT NULL,
  `description` char(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`commandid`),
  KEY `prov` (`prov`)
) ENGINE=MyISAM AUTO_INCREMENT=682 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `command_assign`
--

DROP TABLE IF EXISTS `command_assign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `command_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commandid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `unique_name` char(200) DEFAULT NULL,
  `unique_command` char(100) DEFAULT NULL,
  `assign_time` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cmd` (`commandid`),
  KEY `usr` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=243 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command_id` int(11) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`command_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sp`
--

DROP TABLE IF EXISTS `sp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(200) DEFAULT NULL,
  `password` char(50) DEFAULT NULL,
  `name` char(200) DEFAULT NULL,
  `priority` int(1) DEFAULT NULL,
  `mo_url` varchar(255) DEFAULT NULL,
  `sr_url` varchar(255) DEFAULT NULL,
  `mt_url` varchar(255) DEFAULT NULL,
  `ivr_url` varchar(255) DEFAULT NULL,
  `send_mt` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pri` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-10-11 21:12:42
