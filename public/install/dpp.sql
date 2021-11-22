-- MySQL dump 10.13  Distrib 5.5.62, for Linux (x86_64)
--
-- Host: localhost    Database: xinyu_in
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
-- Table structure for table `kpl_affiche`
--

DROP TABLE IF EXISTS `kpl_affiche`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_affiche` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `text` varchar(3000) NOT NULL COMMENT '内容',
  `time` varchar(255) NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='公告';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_affiche`
--

LOCK TABLES `kpl_affiche` WRITE;
/*!40000 ALTER TABLE `kpl_affiche` DISABLE KEYS */;
INSERT INTO `kpl_affiche` VALUES (1,'HELLO,WORLD','1636637059');
/*!40000 ALTER TABLE `kpl_affiche` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_goods`
--

DROP TABLE IF EXISTS `kpl_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `bandwidth` varchar(255) NOT NULL COMMENT '带宽',
  `mysql` varchar(255) NOT NULL COMMENT '数据库大小',
  `web_size` varchar(255) NOT NULL COMMENT '网站大小',
  `ip` varchar(255) NOT NULL,
  `Ypricing` varchar(255) NOT NULL,
  `Npricing` varchar(255) NOT NULL,
  `content` varchar(3000) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_goods`
--

LOCK TABLES `kpl_goods` WRITE;
/*!40000 ALTER TABLE `kpl_goods` DISABLE KEYS */;
INSERT INTO `kpl_goods` VALUES (1,'独享经济版','1','200','1','独享','10','110',''),(2,'独享普惠版','2','300','2','独享','15','150',''),(3,'独享专业版','4','500','10','独享','20','200',''),(4,'独享尊贵版','10','1024','20','独享','30','300','');
/*!40000 ALTER TABLE `kpl_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_host`
--

DROP TABLE IF EXISTS `kpl_host`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_host` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int(10) NOT NULL COMMENT '主机配置ID',
  `user_id` int(10) NOT NULL COMMENT '所属用户ID',
  `time` varchar(255) NOT NULL COMMENT '购买时间',
  `month` int(10) NOT NULL COMMENT '购买月份',
  `bt_id` int(10) NOT NULL COMMENT '对应宝塔ID',
  `ftp` varchar(255) NOT NULL COMMENT 'ftp数据',
  `database` varchar(255) NOT NULL COMMENT '数据库',
  `state` int(10) NOT NULL DEFAULT '0' COMMENT '服务器状态 0正常 1到期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户虚拟机';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_host`
--

LOCK TABLES `kpl_host` WRITE;
/*!40000 ALTER TABLE `kpl_host` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpl_host` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_index`
--

DROP TABLE IF EXISTS `kpl_index`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_index` (
  `key` varchar(255) NOT NULL COMMENT '键名',
  `value` varchar(2000) NOT NULL COMMENT '配置',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_index`
--

LOCK TABLES `kpl_index` WRITE;
/*!40000 ALTER TABLE `kpl_index` DISABLE KEYS */;
INSERT INTO `kpl_index` VALUES ('admin','{\"password\":\"21232f297a57a5a743894a0e4a801fc3\",\"count\":0}'),('frontend','{\"title\":\"\\u5f00\\u666e\\u52d2-\\u5b9d\\u5854IDC\\u5206\\u9500\\u7cfb\\u7edf\",\"keywords\":\"\\u670d\\u52a1\\u5668\\u5206\\u9500\\uff0cIDC\\uff0c\\u5f00\\u666e\\u52d2\\uff0c\\u5f00\\u666e\\u52d2IDC\\uff0c\\u5b9d\\u5854\",\"descript\":\"\\u5f00\\u666e\\u52d2\\u56fd\\u5185\\u6700\\u5927\\u7684IDC\\u5206\\u9500\\u5e73\\u53f0\",\"copyright\":\"\\u4f5c\\u8005\\uff1a\\u7ea2\\u725b QQ\\uff1a32579135\",\"0\":0}'),('theme','default');
/*!40000 ALTER TABLE `kpl_index` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_menu1`
--

DROP TABLE IF EXISTS `kpl_menu1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_menu1` (
  `gid` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '跳转链接',
  `time` varchar(255) NOT NULL COMMENT '创建时间',
  UNIQUE KEY `gid` (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='一级菜单栏';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_menu1`
--

LOCK TABLES `kpl_menu1` WRITE;
/*!40000 ALTER TABLE `kpl_menu1` DISABLE KEYS */;
INSERT INTO `kpl_menu1` VALUES (1,'菜单一','','1622963354'),(2,'菜单二','https://yun.joo.life','1622963354');
/*!40000 ALTER TABLE `kpl_menu1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_menu2`
--

DROP TABLE IF EXISTS `kpl_menu2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_menu2` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `gid` int(10) NOT NULL COMMENT '所属id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '跳转链接',
  `time` varchar(255) NOT NULL COMMENT '发布时间',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='二级菜单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_menu2`
--

LOCK TABLES `kpl_menu2` WRITE;
/*!40000 ALTER TABLE `kpl_menu2` DISABLE KEYS */;
INSERT INTO `kpl_menu2` VALUES (1,1,'二级菜单1','yun.joo.life','1622963907'),(2,1,'二级菜单2','yun.joo.life','1622963907');
/*!40000 ALTER TABLE `kpl_menu2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_orders`
--

DROP TABLE IF EXISTS `kpl_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_orders` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` varchar(255) NOT NULL COMMENT '订单ID',
  `order_state` int(10) NOT NULL DEFAULT '1' COMMENT '订单状态 0=已支付 1=待支付 2=已过期',
  `order_pay` varchar(255) NOT NULL COMMENT '支付方式 alipay =支付宝 wxpay=微信',
  `order_price` varchar(255) NOT NULL COMMENT '支付金额',
  `order_gid` int(10) NOT NULL COMMENT 'goodsID商品id',
  `order_input` int(10) NOT NULL COMMENT '购买时常',
  `order_time` varchar(255) NOT NULL COMMENT '购买时间',
  `user_id` int(10) NOT NULL COMMENT '下单用户id',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_orders`
--

LOCK TABLES `kpl_orders` WRITE;
/*!40000 ALTER TABLE `kpl_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpl_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_shuffling`
--

DROP TABLE IF EXISTS `kpl_shuffling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_shuffling` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `imgSrc` varchar(255) NOT NULL COMMENT '图片链接',
  `text` varchar(255) NOT NULL COMMENT '描述',
  `addtime` varchar(255) NOT NULL COMMENT '保存时间',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_shuffling`
--

LOCK TABLES `kpl_shuffling` WRITE;
/*!40000 ALTER TABLE `kpl_shuffling` DISABLE KEYS */;
INSERT INTO `kpl_shuffling` VALUES (1,'https://images.pexels.com/photos/9734976/pexels-photo-9734976.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940','dome1','1636637059');
/*!40000 ALTER TABLE `kpl_shuffling` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_user`
--

DROP TABLE IF EXISTS `kpl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(255) NOT NULL COMMENT '账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `state` int(10) NOT NULL DEFAULT '0' COMMENT '状态 0=正常 1=封禁',
  `add_time` varchar(255) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_user`
--

LOCK TABLES `kpl_user` WRITE;
/*!40000 ALTER TABLE `kpl_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpl_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_work_order`
--

DROP TABLE IF EXISTS `kpl_work_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_work_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` varchar(255) NOT NULL COMMENT '问题类型',
  `describe` varchar(2000) NOT NULL COMMENT '问题描述',
  `username` varchar(255) NOT NULL COMMENT '登录账号',
  `password` varchar(255) NOT NULL COMMENT '登录密码',
  `state` int(10) NOT NULL DEFAULT '1' COMMENT '1=待处理 2=已回复 3=已完结',
  `userID` int(10) NOT NULL COMMENT '用户id',
  `work_order_number` varchar(255) NOT NULL COMMENT '订单编号',
  `time` varchar(120) NOT NULL COMMENT '创建时间',
  `is_look` int(10) NOT NULL COMMENT '0=>已读 1=>未读',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='工单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_work_order`
--

LOCK TABLES `kpl_work_order` WRITE;
/*!40000 ALTER TABLE `kpl_work_order` DISABLE KEYS */;
INSERT INTO `kpl_work_order` VALUES (1,'服务器问题','这是一条测试的问题描述','admin','admin',3,1,'3272704502','1636352251',0),(2,'服务器问题','这是一条测试的工单 问题描述','admin','admin',3,1,'3272704990','1636352495',0),(3,'服务器问题','测试问题描述','admin','admin',3,1,'3272705668','1636352834',0),(4,'服务器问题','问题描述','admin','admin',1,1,'3272706462','1636353231',0),(5,'服务器问题','问题描述','admin','admin',1,1,'32727012312','1636353231',0);
/*!40000 ALTER TABLE `kpl_work_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpl_work_order_page`
--

DROP TABLE IF EXISTS `kpl_work_order_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpl_work_order_page` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `text` varchar(3000) NOT NULL COMMENT '内容',
  `user_id` varchar(255) NOT NULL COMMENT '用户id',
  `wk_id` varchar(255) NOT NULL COMMENT '工单ID',
  `time` varchar(255) NOT NULL COMMENT '发布时间',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpl_work_order_page`
--

LOCK TABLES `kpl_work_order_page` WRITE;
/*!40000 ALTER TABLE `kpl_work_order_page` DISABLE KEYS */;
INSERT INTO `kpl_work_order_page` VALUES (1,'请耐心等待 您的订单正在处理中。。。。','','4','1636522523'),(2,'谢谢官方','','4','1636522582'),(3,'谢谢','1','4','1636522627'),(4,'不客气','','4','1636522642'),(5,'服务器无法正常解析域名','1','4','1636523753');
/*!40000 ALTER TABLE `kpl_work_order_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'xinyu_in'
--

--
-- Dumping routines for database 'xinyu_in'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-22 14:15:17
