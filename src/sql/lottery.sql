-- MySQL dump 10.13  Distrib 5.5.62, for Linux (x86_64)
--
-- Host: localhost    Database: testdb
-- ------------------------------------------------------
-- Server version	5.5.62-log

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
-- Table structure for table `db_lott_activity`
--

DROP TABLE IF EXISTS `db_lott_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_lott_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL COMMENT '活动标题',
  `imgUrl` varchar(64) DEFAULT NULL COMMENT '活动封面图片url',
  `description` text COMMENT '活动描述',
  `starttime` int(11) DEFAULT NULL COMMENT '活动开始时间',
  `endtime` int(11) DEFAULT NULL COMMENT '活动结束时间',
  `createtime` int(11) DEFAULT NULL COMMENT '活动录入时间',
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db_lott_activity`
--

LOCK TABLES `db_lott_activity` WRITE;
/*!40000 ALTER TABLE `db_lott_activity` DISABLE KEYS */;
INSERT INTO `db_lott_activity` VALUES (1,'圣诞节活动','http://pic.5tu.cn/uploads/allimg/1512/081643368590.jpg','圣诞节活动',1608307200,1608912000,1608371813),(2,'元旦','','元旦',1608566400,1609603200,1608604335);
/*!40000 ALTER TABLE `db_lott_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `db_lott_prize`
--

DROP TABLE IF EXISTS `db_lott_prize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_lott_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activityId` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `name` varchar(32) DEFAULT NULL COMMENT '奖品名称',
  `imgUrl` varchar(64) DEFAULT NULL COMMENT '奖品配图',
  `num` int(11) DEFAULT '0' COMMENT '奖品数量',
  `lott_num` int(11) DEFAULT '0' COMMENT '已被抽中数量',
  `basenumber` int(11) DEFAULT '0' COMMENT '中奖基数',
  `state` tinyint(1) DEFAULT '1' COMMENT '1代表真实中奖，0代表空奖，谢谢参与',
  `createtime` int(11) DEFAULT '0' COMMENT '录入时间，时间戳',
  `sortnum` int(11) DEFAULT '0' COMMENT '奖项排序',
  PRIMARY KEY (`id`),
  KEY `activityId` (`activityId`) USING BTREE,
  KEY `state` (`state`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db_lott_prize`
--

LOCK TABLES `db_lott_prize` WRITE;
/*!40000 ALTER TABLE `db_lott_prize` DISABLE KEYS */;
INSERT INTO `db_lott_prize` VALUES (1,1,'京东卡100元','http://www.qiangbus.com/res/images/redpacket.png',5,5,0,1,1608372318,1),(2,1,'话费10元','http://www.qiangbus.com/res/images/redpacket.png',50,16,30,1,1608372318,2),(3,1,'ipad mini 4','http://www.qiangbus.com/res/images/redpacket.png',0,0,0,1,1608372318,3),(4,1,'谢谢参与','http://www.qiangbus.com/res/images/redpacket.png',0,0,50,0,1608372318,4),(5,1,'话费30元','http://www.qiangbus.com/res/images/redpacket.png',20,12,10,1,1608372318,5),(6,1,'话费50元','http://www.qiangbus.com/res/images/redpacket.png',0,0,0,1,1608372318,6),(7,2,'话费50元','',10,0,10,1,1608372318,1),(8,2,'话费10元','',20,1,20,1,1608372318,2),(9,2,'流量10M','',30,0,60,1,1608372318,3),(10,2,'流量100M','',10,0,10,1,1608372318,4);
/*!40000 ALTER TABLE `db_lott_prize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `db_lott_record`
--

DROP TABLE IF EXISTS `db_lott_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_lott_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `activityId` int(11) DEFAULT '0' COMMENT '活动id',
  `activitytitle` varchar(64) DEFAULT NULL COMMENT '活动标题',
  `prizeId` int(11) DEFAULT '0' COMMENT '奖品id',
  `prizename` varchar(32) DEFAULT NULL COMMENT '奖品名称',
  `state` tinyint(1) DEFAULT '1' COMMENT '1代表真实中奖，0代表空奖，谢谢参与',
  `lotterytime` int(11) DEFAULT NULL COMMENT '抽奖时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `activityId` (`activityId`) USING BTREE,
  KEY `prizeId` (`prizeId`) USING BTREE,
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db_lott_record`
--

LOCK TABLES `db_lott_record` WRITE;
/*!40000 ALTER TABLE `db_lott_record` DISABLE KEYS */;
INSERT INTO `db_lott_record` VALUES (1,1,1,'圣诞节活动',2,'话费10元',1,1608446513),(2,1,1,'圣诞节活动',5,'话费30元',1,1608446527),(3,1,1,'圣诞节活动',4,'谢谢参与',0,1608446717),(4,1,1,'圣诞节活动',2,'话费10元',1,1608550656),(5,1,1,'圣诞节活动',5,'话费30元',1,1608550669),(6,1,1,'圣诞节活动',2,'话费10元',1,1608550681),(7,1,1,'圣诞节活动',2,'话费10元',1,1608617924),(8,1,1,'圣诞节活动',4,'谢谢参与',0,1608617946),(9,1,1,'圣诞节活动',2,'话费10元',1,1608617970),(10,1,1,'圣诞节活动',5,'话费30元',1,1608618102),(11,1,1,'圣诞节活动',5,'话费30元',1,1608618124),(12,1,1,'圣诞节活动',2,'话费10元',1,1608618134),(13,1,2,'元旦',8,'话费10元',1,1608618447),(14,1,1,'圣诞节活动',5,'话费30元',1,1608624474),(15,1,1,'圣诞节活动',2,'话费10元',1,1608624484),(16,1,1,'圣诞节活动',2,'话费10元',1,1608624495),(17,1,1,'圣诞节活动',5,'话费30元',1,1608820856),(18,1,1,'圣诞节活动',4,'谢谢参与',0,1608821303),(19,1,1,'圣诞节活动',2,'话费10元',1,1608821312),(20,1,1,'圣诞节活动',4,'谢谢参与',0,1608821320);
/*!40000 ALTER TABLE `db_lott_record` ENABLE KEYS */;
UNLOCK TABLES;


-- Dump completed on 2020-12-26 15:24:26
