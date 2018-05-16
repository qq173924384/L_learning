/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2016-08-30 00:02:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '1',
  `role_id` int(11) NOT NULL,
  `login` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', '1', '4', 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- ----------------------------
-- Table structure for admin_meta
-- ----------------------------
DROP TABLE IF EXISTS `admin_meta`;
CREATE TABLE `admin_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `meta_key` (`meta_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_meta
-- ----------------------------

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `verifiy` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：未审核，1：审核通过',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `create_time` datetime NOT NULL DEFAULT '2016-01-01 00:00:00',
  `edit_time` datetime NOT NULL DEFAULT '2016-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1', '1', '14', '0', '标题', '关键词', '描述', '<p>内容</p>', '2016-01-01 00:00:00', '2016-01-01 00:00:00');
INSERT INTO `article` VALUES ('3', '1', '14', '0', '文章2', '文章2文章2', '文章2文章2文章2', '<p>文章2文章2文章2文章2</p>', '2016-08-29 10:05:12', '2016-08-29 10:05:12');
INSERT INTO `article` VALUES ('4', '1', '14', '0', '文章3文章3', '文章3', '文章3', '<p>文章3文章3文章3</p>', '2016-08-29 11:11:16', '2016-08-29 11:41:16');
INSERT INTO `article` VALUES ('5', '1', '14', '0', '文章4文章4', '文章4文章4', '文章4文章4文章4', '<p>文章4文章4文章4文章4</p>', '2016-08-29 11:24:07', '2016-08-29 14:59:03');
INSERT INTO `article` VALUES ('6', '1', '14', '0', '文章5', '文章5', '文章5', '<p>文章5文章5文章5</p>', '2016-08-29 15:07:50', '2016-08-29 15:07:50');

-- ----------------------------
-- Table structure for cate
-- ----------------------------
DROP TABLE IF EXISTS `cate`;
CREATE TABLE `cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `tree` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `tree` (`tree`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of cate
-- ----------------------------
INSERT INTO `cate` VALUES ('14', '1', '0', '默认分类', '14');
INSERT INTO `cate` VALUES ('15', '1', '14', '子分类123', '14-15');

-- ----------------------------
-- Table structure for gallery
-- ----------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'new image',
  `src` varchar(255) NOT NULL,
  `edit_time` datetime NOT NULL DEFAULT '2016-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gallery
-- ----------------------------
INSERT INTO `gallery` VALUES ('5', '1', 'QQ截图20160818152819.jpg', '/upload/img/e576112af310ef54e2f8bbca6b8c8e36.jpg', '2016-08-24 06:21:15');
INSERT INTO `gallery` VALUES ('4', '1', '20eca87184c3c650c72e5060d195f0c9.jpg', '/upload/img/06e2e9a202d12aec7d8e7a798d65ccea.jpg', '2016-08-24 06:21:15');

-- ----------------------------
-- Table structure for page
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of page
-- ----------------------------
INSERT INTO `page` VALUES ('1', '0', '新页面', 'new_site', '新页面', '', '');

-- ----------------------------
-- Table structure for page_meta
-- ----------------------------
DROP TABLE IF EXISTS `page_meta`;
CREATE TABLE `page_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`page_id`),
  KEY `meta_key` (`meta_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of page_meta
-- ----------------------------

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rights` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('4', '管理员', '{\"index\":\"true\",\"index-index\":\"true\",\"index-setting\":\"true\",\"index-setMenu\":\"true\",\"manager\":\"true\",\"manager-index\":\"true\",\"manager-role\":\"true\",\"site\":\"true\",\"site-index\":\"true\",\"site-page\":\"true\",\"site-gallery\":\"true\",\"site-article\":\"true\",\"site-addArticle\":\"true\",\"site-cate\":\"true\"}');

-- ----------------------------
-- Table structure for site
-- ----------------------------
DROP TABLE IF EXISTS `site`;
CREATE TABLE `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of site
-- ----------------------------
INSERT INTO `site` VALUES ('1', '主站', '主站123', '');
INSERT INTO `site` VALUES ('2', 'haha', 'haha', 'new_site');

-- ----------------------------
-- Table structure for site_meta
-- ----------------------------
DROP TABLE IF EXISTS `site_meta`;
CREATE TABLE `site_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`site_id`),
  KEY `meta_key` (`meta_key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of site_meta
-- ----------------------------
