/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 80012
Source Host           : localhost:3306
Source Database       : rpa

Target Server Type    : MYSQL
Target Server Version : 80012
File Encoding         : 65001

Date: 2019-12-29 23:50:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sys_login_records
-- ----------------------------
DROP TABLE IF EXISTS `sys_login_records`;
CREATE TABLE `sys_login_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(6) DEFAULT NULL COMMENT '用户ID',
  `ip` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'IP地址',
  `country` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '国家',
  `area` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `region` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '地区',
  `city` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '城市',
  `county` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '县',
  `isp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '服务提供商',
  `country_id` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `area_id` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `region_id` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city_id` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `county_id` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isp_id` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isoCode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '国家代号',
  `country_2` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '国家名称，备方案查询结果',
  `region_2` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '省份,备方案查询结果',
  `city_2` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '城市，备方案查询结果',
  `postal` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '邮编',
  `latitude` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '纬度',
  `longitude` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '经度',
  `traits_net` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '网关',
  `device` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备名称',
  `device_type` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备类型',
  `browser` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '浏览器',
  `platform` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作系统',
  `language` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '语言',
  `login_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户登录行为分析表';
