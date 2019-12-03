/*
Navicat MySQL Data Transfer

Source Server         : 172.16.253.170_3306
Source Server Version : 50724
Source Host           : 172.16.253.170:3306
Source Database       : rpa

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2019-12-03 18:28:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sys_dept_functionals
-- ----------------------------
DROP TABLE IF EXISTS `sys_dept_functionals`;
CREATE TABLE `sys_dept_functionals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '岗位名称',
  `unique_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='职务表';

-- ----------------------------
-- Records of sys_dept_functionals
-- ----------------------------
INSERT INTO `sys_dept_functionals` VALUES ('1', '管理', 'GL', null, null);
INSERT INTO `sys_dept_functionals` VALUES ('2', '部门经理', 'BMJL', null, null);
INSERT INTO `sys_dept_functionals` VALUES ('3', '部门主管', 'BMZG', null, null);
INSERT INTO `sys_dept_functionals` VALUES ('4', '员工', 'YG', '2019-11-29 11:22:16', '2019-11-29 11:22:16');
INSERT INTO `sys_dept_functionals` VALUES ('5', '总部经理', 'ZBJL', '2019-12-03 14:20:35', '2019-12-03 14:20:35');

-- ----------------------------
-- Table structure for sys_dept_post_relations
-- ----------------------------
DROP TABLE IF EXISTS `sys_dept_post_relations`;
CREATE TABLE `sys_dept_post_relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` mediumint(4) NOT NULL COMMENT '部门id',
  `post_id` mediumint(4) NOT NULL COMMENT '岗位id',
  `fullname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '全名',
  `duty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '岗位职责',
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '任职资格',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='部门岗位关系表';

-- ----------------------------
-- Records of sys_dept_post_relations
-- ----------------------------
INSERT INTO `sys_dept_post_relations` VALUES ('1', '14', '5', '金融科技部经理', null, null, '部门经理', '2019-11-29 14:09:28', '2019-12-03 14:54:25');
INSERT INTO `sys_dept_post_relations` VALUES ('6', '14', '7', '金融科技部主管', null, null, '部门主管', '2019-11-29 14:47:15', '2019-12-03 14:54:31');
INSERT INTO `sys_dept_post_relations` VALUES ('24', '5', '1', '董事长', null, null, '董事长', '2019-12-03 13:37:02', '2019-12-03 14:52:21');
INSERT INTO `sys_dept_post_relations` VALUES ('25', '6', '2', '总经理', null, null, '总经理', '2019-12-03 13:42:43', '2019-12-03 14:52:18');
INSERT INTO `sys_dept_post_relations` VALUES ('26', '6', '23', '常务副总经理', null, null, '常务副总经理', '2019-12-03 13:44:07', '2019-12-03 14:52:23');
INSERT INTO `sys_dept_post_relations` VALUES ('27', '14', '9', '员工', null, null, '员工', '2019-12-03 13:57:53', '2019-12-03 15:02:09');
INSERT INTO `sys_dept_post_relations` VALUES ('28', '15', '4', '营销服务管理总部经理', null, null, '总部经理', '2019-12-03 14:19:13', '2019-12-03 14:53:39');
INSERT INTO `sys_dept_post_relations` VALUES ('29', '47', '5', '客户服务部经理', null, null, '部门经理', '2019-12-03 14:23:25', '2019-12-03 14:53:38');
INSERT INTO `sys_dept_post_relations` VALUES ('30', '47', '9', '开户专员', null, null, '开户专员', '2019-12-03 14:26:12', '2019-12-03 14:52:26');
INSERT INTO `sys_dept_post_relations` VALUES ('31', '47', '7', '开户主管', null, null, '开户主管', '2019-12-03 14:26:26', '2019-12-03 14:52:28');

-- ----------------------------
-- Table structure for sys_dept_posts
-- ----------------------------
DROP TABLE IF EXISTS `sys_dept_posts`;
CREATE TABLE `sys_dept_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '职能名称',
  `rank` mediumint(9) DEFAULT NULL COMMENT '等级',
  `unique_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='岗位表';

-- ----------------------------
-- Records of sys_dept_posts
-- ----------------------------
INSERT INTO `sys_dept_posts` VALUES ('1', '董事长', '10', 'DSZ', null, '2019-11-28 16:14:42');
INSERT INTO `sys_dept_posts` VALUES ('2', '总经理', '20', 'ZJL', null, '2019-12-03 13:21:47');
INSERT INTO `sys_dept_posts` VALUES ('3', '副总经理', '30', 'FZJL', null, '2019-12-03 13:21:53');
INSERT INTO `sys_dept_posts` VALUES ('4', '总部经理', '40', 'ZBJL', null, '2019-12-03 13:21:58');
INSERT INTO `sys_dept_posts` VALUES ('5', '部门经理', '50', 'BMJL', null, '2019-12-03 10:50:34');
INSERT INTO `sys_dept_posts` VALUES ('6', '部门副经理', '60', 'BMFJL', null, '2019-12-03 10:50:38');
INSERT INTO `sys_dept_posts` VALUES ('7', '部门主管', '70', 'BMZG', null, '2019-12-03 10:50:28');
INSERT INTO `sys_dept_posts` VALUES ('8', '部门组长', '80', 'BMZZ', null, '2019-12-03 13:22:08');
INSERT INTO `sys_dept_posts` VALUES ('9', '员工', '90', 'YG', null, '2019-12-03 13:22:11');
INSERT INTO `sys_dept_posts` VALUES ('23', '常务副总经理', '30', 'CWFZJL', '2019-12-03 13:43:40', '2019-12-03 13:43:47');

-- ----------------------------
-- Table structure for sys_dept_relations
-- ----------------------------
DROP TABLE IF EXISTS `sys_dept_relations`;
CREATE TABLE `sys_dept_relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_relation_id` mediumint(4) NOT NULL COMMENT 'sys_dept_post_relations 表id',
  `admin_id` mediumint(4) NOT NULL COMMENT '人id',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='岗位和人关系';

-- ----------------------------
-- Records of sys_dept_relations
-- ----------------------------
INSERT INTO `sys_dept_relations` VALUES ('20', '6', '1', '2019-12-02 17:26:57', '2019-12-03 13:53:34');
INSERT INTO `sys_dept_relations` VALUES ('21', '6', '98', '2019-12-03 10:02:23', '2019-12-03 10:02:23');
INSERT INTO `sys_dept_relations` VALUES ('22', '24', '100', '2019-12-03 13:40:51', '2019-12-03 13:40:51');
INSERT INTO `sys_dept_relations` VALUES ('23', '25', '101', '2019-12-03 13:47:10', '2019-12-03 13:47:10');
INSERT INTO `sys_dept_relations` VALUES ('24', '26', '102', '2019-12-03 13:50:02', '2019-12-03 13:50:02');
INSERT INTO `sys_dept_relations` VALUES ('25', '1', '103', '2019-12-03 14:01:23', '2019-12-03 14:01:23');
INSERT INTO `sys_dept_relations` VALUES ('26', '6', '85', '2019-12-03 14:07:53', '2019-12-03 14:07:53');
INSERT INTO `sys_dept_relations` VALUES ('27', '27', '77', '2019-12-03 14:08:26', '2019-12-03 14:08:26');
INSERT INTO `sys_dept_relations` VALUES ('29', '27', '18', '2019-12-03 14:09:28', '2019-12-03 14:09:28');
INSERT INTO `sys_dept_relations` VALUES ('30', '27', '35', '2019-12-03 14:09:55', '2019-12-03 14:09:55');
INSERT INTO `sys_dept_relations` VALUES ('31', '28', '104', '2019-12-03 14:22:28', '2019-12-03 14:22:28');
INSERT INTO `sys_dept_relations` VALUES ('32', '29', '71', '2019-12-03 14:24:32', '2019-12-03 14:24:32');
INSERT INTO `sys_dept_relations` VALUES ('33', '31', '41', '2019-12-03 14:27:14', '2019-12-03 14:27:14');
INSERT INTO `sys_dept_relations` VALUES ('34', '30', '43', '2019-12-03 14:30:11', '2019-12-03 14:30:11');
INSERT INTO `sys_dept_relations` VALUES ('37', '27', '81', '2019-12-03 15:47:32', '2019-12-03 15:47:32');

-- ----------------------------
-- Table structure for sys_depts
-- ----------------------------
DROP TABLE IF EXISTS `sys_depts`;
CREATE TABLE `sys_depts` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `pid` int(6) DEFAULT '0' COMMENT '父级id',
  `manager_id` mediumint(4) unsigned DEFAULT NULL COMMENT '负责人id',
  `leader_id` mediumint(4) unsigned DEFAULT NULL COMMENT '分管领导id',
  `path` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路径',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '部门名称',
  `order` tinyint(3) DEFAULT '1' COMMENT '排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `post_ids` text COLLATE utf8mb4_unicode_ci COMMENT '岗位json',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='组织架构';

-- ----------------------------
-- Records of sys_depts
-- ----------------------------
INSERT INTO `sys_depts` VALUES ('1', '0', null, null, ',1,', '华安期货有限责任公司', '1', '2019-11-25 14:43:11', '2019-11-25 14:43:57', null);
INSERT INTO `sys_depts` VALUES ('2', '1', null, null, ',1,2,', '华安期货有限责任公司', '1', '2019-11-25 14:43:11', '2019-11-26 10:13:31', null);
INSERT INTO `sys_depts` VALUES ('3', '1', null, null, ',1,3,', '安徽华安资本管理有限责任公司', '1', '2019-11-25 14:43:36', '2019-11-25 14:43:36', null);
INSERT INTO `sys_depts` VALUES ('5', '2', null, null, ',1,2,5,', '董事会', '1', '2019-11-25 15:12:57', '2019-11-25 15:12:57', null);
INSERT INTO `sys_depts` VALUES ('6', '2', null, null, ',1,2,6,', '总经办', '1', '2019-11-25 15:13:07', '2019-11-25 15:13:07', null);
INSERT INTO `sys_depts` VALUES ('7', '2', null, null, ',1,2,7,', '办公室', '1', '2019-11-25 15:13:12', '2019-11-25 15:13:12', null);
INSERT INTO `sys_depts` VALUES ('8', '2', null, null, ',1,2,8,', '合规风险管理总部', '1', '2019-11-25 15:13:26', '2019-11-25 15:13:26', null);
INSERT INTO `sys_depts` VALUES ('9', '2', null, null, ',1,2,9,', '财务部', '1', '2019-11-25 15:13:36', '2019-11-25 15:13:36', null);
INSERT INTO `sys_depts` VALUES ('10', '2', null, null, ',1,2,10,', '行政部', '1', '2019-11-25 15:13:43', '2019-11-25 15:13:43', null);
INSERT INTO `sys_depts` VALUES ('11', '2', null, null, ',1,2,11,', '清算交割中心', '1', '2019-11-25 15:13:53', '2019-11-25 15:13:53', null);
INSERT INTO `sys_depts` VALUES ('12', '2', null, null, ',1,2,12,', '交易运营管理总部', '1', '2019-11-25 15:14:11', '2019-11-25 15:14:11', null);
INSERT INTO `sys_depts` VALUES ('13', '2', null, null, ',1,2,13,', '信息技术部', '1', '2019-11-25 15:14:18', '2019-11-25 15:14:18', null);
INSERT INTO `sys_depts` VALUES ('14', '2', '103', null, ',1,2,14,', '金融科技部', '1', '2019-11-25 15:14:25', '2019-12-03 14:11:25', null);
INSERT INTO `sys_depts` VALUES ('15', '2', null, null, ',1,2,15,', '营销服务管理总部', '1', '2019-11-25 15:14:58', '2019-11-25 15:14:58', null);
INSERT INTO `sys_depts` VALUES ('16', '2', null, null, ',1,2,16,', '资产管理部', '1', '2019-11-25 15:15:09', '2019-11-25 15:15:09', null);
INSERT INTO `sys_depts` VALUES ('17', '2', null, null, ',1,2,17,', '投资咨询部', '1', '2019-11-25 15:15:16', '2019-11-25 15:15:16', null);
INSERT INTO `sys_depts` VALUES ('18', '2', null, null, ',1,2,18,', '研究所', '1', '2019-11-25 15:15:38', '2019-11-25 15:15:38', null);
INSERT INTO `sys_depts` VALUES ('19', '2', null, null, ',1,2,19,', 'IB事业部', '1', '2019-11-25 15:15:45', '2019-11-25 15:15:45', null);
INSERT INTO `sys_depts` VALUES ('20', '2', null, null, ',1,2,20,', '金融期货部', '1', '2019-11-25 15:15:52', '2019-11-25 15:15:52', null);
INSERT INTO `sys_depts` VALUES ('21', '2', null, null, ',1,2,21,', '商品期货部', '1', '2019-11-25 15:16:11', '2019-11-25 15:16:11', null);
INSERT INTO `sys_depts` VALUES ('22', '2', null, null, ',1,2,22,', '创新发展部', '1', '2019-11-25 15:16:19', '2019-11-25 15:16:19', null);
INSERT INTO `sys_depts` VALUES ('23', '2', null, null, ',1,2,23,', '投资发展部', '1', '2019-11-25 15:16:25', '2019-11-25 15:16:25', null);
INSERT INTO `sys_depts` VALUES ('24', '2', null, null, ',1,2,24,', '上海分公司', '1', '2019-11-25 15:16:37', '2019-11-25 15:16:37', null);
INSERT INTO `sys_depts` VALUES ('25', '2', null, null, ',1,2,25,', '深圳分公司', '1', '2019-11-25 15:16:47', '2019-11-25 15:16:47', null);
INSERT INTO `sys_depts` VALUES ('26', '2', null, null, ',1,2,26,', '大连会展路营业部', '1', '2019-11-25 15:17:01', '2019-11-25 15:17:01', null);
INSERT INTO `sys_depts` VALUES ('27', '2', null, null, ',1,2,27,', '郑州营业部', '1', '2019-11-25 15:17:10', '2019-11-25 15:17:10', null);
INSERT INTO `sys_depts` VALUES ('28', '2', null, null, ',1,2,28,', '青岛营业部', '1', '2019-11-25 15:17:21', '2019-11-25 15:17:21', null);
INSERT INTO `sys_depts` VALUES ('29', '2', null, null, ',1,2,29,', '金华营业部', '1', '2019-11-25 15:17:39', '2019-11-25 15:17:39', null);
INSERT INTO `sys_depts` VALUES ('30', '2', null, null, ',1,2,30,', '芜湖营业部', '1', '2019-11-25 15:17:49', '2019-11-25 15:17:49', null);
INSERT INTO `sys_depts` VALUES ('31', '2', null, null, ',1,2,31,', '马鞍山营业部', '1', '2019-11-25 15:17:57', '2019-11-25 15:17:57', null);
INSERT INTO `sys_depts` VALUES ('32', '2', null, null, ',1,2,32,', '安庆营业部', '1', '2019-11-25 15:18:03', '2019-11-25 15:18:03', null);
INSERT INTO `sys_depts` VALUES ('33', '2', null, null, ',1,2,33,', '阜阳营业部', '1', '2019-11-25 15:18:10', '2019-11-25 15:18:10', null);
INSERT INTO `sys_depts` VALUES ('34', '2', null, null, ',1,2,34,', '杭州营业部', '1', '2019-11-25 15:18:15', '2019-11-25 15:18:15', null);
INSERT INTO `sys_depts` VALUES ('35', '2', null, null, ',1,2,35,', '铜陵营业部', '1', '2019-11-25 15:18:28', '2019-11-25 15:18:28', null);
INSERT INTO `sys_depts` VALUES ('36', '2', null, null, ',1,2,36,', '长春营业部', '1', '2019-11-25 15:18:38', '2019-11-25 15:18:38', null);
INSERT INTO `sys_depts` VALUES ('37', '2', null, null, ',1,2,37,', '出市代表', '1', '2019-11-25 15:18:52', '2019-11-25 15:18:52', null);
INSERT INTO `sys_depts` VALUES ('47', '15', '71', '104', ',1,2,15,47,', '客户服务部', '1', '2019-11-29 10:15:12', '2019-12-03 14:30:42', null);
INSERT INTO `sys_depts` VALUES ('54', '15', null, null, ',1,2,15,54,', '互联网金融部', '1', '2019-12-02 11:12:53', '2019-12-02 11:12:53', null);
INSERT INTO `sys_depts` VALUES ('60', '8', null, null, ',1,2,8,60,', '合规审查部', '1', '2019-12-03 13:23:57', '2019-12-03 13:23:57', null);
INSERT INTO `sys_depts` VALUES ('61', '8', null, null, ',1,2,8,61,', '法务部', '1', '2019-12-03 13:24:53', '2019-12-03 13:24:53', null);
INSERT INTO `sys_depts` VALUES ('62', '11', null, null, ',1,2,11,62,', '清算部', '1', '2019-12-03 13:25:14', '2019-12-03 13:25:14', null);
INSERT INTO `sys_depts` VALUES ('63', '11', null, null, ',1,2,11,63,', '交割部', '1', '2019-12-03 13:25:21', '2019-12-03 13:25:21', null);
INSERT INTO `sys_depts` VALUES ('64', '15', null, null, ',1,2,15,64,', '市场运营部', '1', '2019-12-03 13:26:09', '2019-12-03 13:26:09', null);
INSERT INTO `sys_depts` VALUES ('65', '16', null, null, ',1,2,16,65,', '投资研究部', '1', '2019-12-03 13:26:28', '2019-12-03 13:26:28', null);
INSERT INTO `sys_depts` VALUES ('66', '16', null, null, ',1,2,16,66,', '产品部', '1', '2019-12-03 13:26:34', '2019-12-03 13:26:34', null);
INSERT INTO `sys_depts` VALUES ('67', '16', null, null, ',1,2,16,67,', '投资管理部', '1', '2019-12-03 13:26:43', '2019-12-03 13:26:43', null);
INSERT INTO `sys_depts` VALUES ('68', '16', null, null, ',1,2,16,68,', '运营部', '1', '2019-12-03 13:26:53', '2019-12-03 13:26:53', null);
INSERT INTO `sys_depts` VALUES ('69', '18', null, null, ',1,2,18,69,', '机构服务部', '1', '2019-12-03 13:27:14', '2019-12-03 13:27:14', null);
INSERT INTO `sys_depts` VALUES ('70', '18', null, null, ',1,2,18,70,', '产品经济研究部', '1', '2019-12-03 13:27:25', '2019-12-03 13:27:25', null);
INSERT INTO `sys_depts` VALUES ('71', '20', null, null, ',1,2,20,71,', '金融期货部市场营销一部', '1', '2019-12-03 13:27:58', '2019-12-03 13:27:58', null);
INSERT INTO `sys_depts` VALUES ('72', '20', null, null, ',1,2,20,72,', '金融期货部市场营销二部', '1', '2019-12-03 13:28:16', '2019-12-03 13:28:16', null);
INSERT INTO `sys_depts` VALUES ('73', '20', null, null, ',1,2,20,73,', '金融期货部业务支持部', '1', '2019-12-03 13:28:30', '2019-12-03 13:28:30', null);
INSERT INTO `sys_depts` VALUES ('74', '24', null, null, ',1,2,24,74,', '上海分公司财务部', '1', '2019-12-03 13:29:14', '2019-12-03 13:29:14', null);
INSERT INTO `sys_depts` VALUES ('75', '24', null, null, ',1,2,24,75,', '上海分公司风控部', '1', '2019-12-03 13:29:29', '2019-12-03 13:29:29', null);
INSERT INTO `sys_depts` VALUES ('76', '24', null, null, ',1,2,24,76,', '上海分公司运营部', '1', '2019-12-03 13:29:45', '2019-12-03 13:29:45', null);
INSERT INTO `sys_depts` VALUES ('77', '24', null, null, ',1,2,24,77,', '上海分公司业务部', '1', '2019-12-03 13:29:53', '2019-12-03 13:29:53', null);
INSERT INTO `sys_depts` VALUES ('78', '24', null, null, ',1,2,24,78,', '上海分公司钢材部', '1', '2019-12-03 13:30:04', '2019-12-03 13:30:04', null);
INSERT INTO `sys_depts` VALUES ('79', '25', null, null, ',1,2,25,79,', '财富中心部', '1', '2019-12-03 13:30:21', '2019-12-03 13:30:21', null);
INSERT INTO `sys_depts` VALUES ('80', '25', null, null, ',1,2,25,80,', '深圳分公司零售业务部', '1', '2019-12-03 13:30:36', '2019-12-03 13:30:36', null);
INSERT INTO `sys_depts` VALUES ('81', '35', null, null, ',1,2,35,81,', '铜陵营业部中小企业服务部', '1', '2019-12-03 13:35:21', '2019-12-03 13:35:21', null);

-- ----------------------------
-- Table structure for sys_flow_groups
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_groups`;
CREATE TABLE `sys_flow_groups` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分组名称',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sys_flow_groups
-- ----------------------------
INSERT INTO `sys_flow_groups` VALUES ('1', '掌厅业务流程', null, null);
INSERT INTO `sys_flow_groups` VALUES ('2', '机器人业务流程', null, null);
INSERT INTO `sys_flow_groups` VALUES ('3', '居间人业务流程', null, null);
INSERT INTO `sys_flow_groups` VALUES ('4', '在线权限申请流程', null, null);
INSERT INTO `sys_flow_groups` VALUES ('5', '自动发起流程', null, null);

-- ----------------------------
-- Table structure for sys_flow_instance_datas
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_instance_datas`;
CREATE TABLE `sys_flow_instance_datas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL DEFAULT '0' COMMENT '实例流程ID',
  `flow_id` int(11) NOT NULL DEFAULT '0' COMMENT '流程ID',
  `field_name` varchar(128) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '文件名',
  `field_value` text COLLATE utf8mb4_bin COMMENT '文件值',
  `field_remark` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '文件备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_id` (`instance_id`),
  KEY `workflow_id` (`flow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='实例数据表';

-- ----------------------------
-- Records of sys_flow_instance_datas
-- ----------------------------
INSERT INTO `sys_flow_instance_datas` VALUES ('33', '41', '1', 'address', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F3132333435362E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('34', '41', '1', 'name', 0xE78E8BE4BA8CE4BAAE, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('35', '41', '1', 'IDCard', 0x333432343232313939313031323132313131, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('36', '41', '1', 'SFZ_ZM', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F53465A5F5A4D2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('37', '41', '1', 'SFZ_FM', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F53465A5F464D2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('38', '41', '1', 'sign', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F7369676E2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('39', '42', '1', 'address', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F3132333435362E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('40', '42', '1', 'name', 0xE78E8BE4BA8CE4BAAE, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('41', '42', '1', 'IDCard', 0x333432343232313939313031323132313131, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('42', '42', '1', 'SFZ_ZM', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F53465A5F5A4D2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('43', '42', '1', 'SFZ_FM', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F53465A5F464D2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('44', '42', '1', 'sign', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F7369676E2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('45', '170', '1', 'address', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F3132333435362E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('46', '170', '1', 'name', 0xE78E8BE4BA8CE4BAAE, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('47', '170', '1', 'IDCard', 0x333432343232313939313031323132313131, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('48', '170', '1', 'SFZ_ZM', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F53465A5F5A4D2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('49', '170', '1', 'SFZ_FM', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F53465A5F464D2E706E67, '', null, null);
INSERT INTO `sys_flow_instance_datas` VALUES ('50', '170', '1', 'sign', 0x73746F726167652F7A742F666C6F772F7A6A62676C632F7369676E2E706E67, '', null, null);

-- ----------------------------
-- Table structure for sys_flow_instance_records
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_instance_records`;
CREATE TABLE `sys_flow_instance_records` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user_id` int(6) NOT NULL COMMENT '参与审批的用户ID',
  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '审核人名称',
  `dept_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '审核人部门',
  `real_user_id` int(6) NOT NULL COMMENT '真实操作人id',
  `real_user_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '真实操作人名称',
  `real_user_dept` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '真实操作人部门',
  `instance_id` int(6) NOT NULL COMMENT '示例的流程id',
  `flow_id` int(6) DEFAULT NULL COMMENT '流程ID',
  `node_id` int(6) NOT NULL COMMENT '流程节点id',
  `node_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `goto_child_id` int(11) DEFAULT NULL COMMENT '前往子流程，记录实例id',
  `is_receive_type` tinyint(1) DEFAULT NULL COMMENT '是否先接受人为主办人',
  `is_sponsor` tinyint(1) DEFAULT '0' COMMENT '是否该步骤主办人 0否， 1是',
  `is_sing_post` tinyint(1) DEFAULT '0' COMMENT '是否会签过',
  `is_back` tinyint(1) DEFAULT '0' COMMENT '是否被退回流程0否 1是',
  `circle` tinyint(1) DEFAULT '1' COMMENT '循环次数',
  `remark` text COLLATE utf8mb4_unicode_ci COMMENT '审批意见',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0为未接收（默认），1为办理中 ,2为已转交,3为已结束4为已打回',
  `is_real` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核人操作人是否同一人',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已读',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否被删除1是',
  `bl_time` datetime NOT NULL COMMENT '办理时间',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='流程处理明细表';

-- ----------------------------
-- Records of sys_flow_instance_records
-- ----------------------------
INSERT INTO `sys_flow_instance_records` VALUES ('190', '1', 'Victor', '金融科技部', '1', 'Victor', '金融科技部', '170', '1', '49', '发起流程', null, null, '0', '0', '0', '1', null, '9', '1', '0', '0', '0000-00-00 00:00:00', '2019-12-03 17:31:45', '2019-12-03 17:31:45');
INSERT INTO `sys_flow_instance_records` VALUES ('191', '85', 'hsuLay', '金融科技部', '0', null, null, '170', '1', '50', '主管审核', null, null, '0', '0', '0', '1', null, '0', '1', '0', '0', '0000-00-00 00:00:00', '2019-12-03 17:31:45', '2019-12-03 17:31:45');

-- ----------------------------
-- Table structure for sys_flow_instances
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_instances`;
CREATE TABLE `sys_flow_instances` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `parent_id` int(6) DEFAULT '0' COMMENT '父节点,0是无父节点',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '唯一标识符',
  `user_id` int(6) DEFAULT NULL COMMENT '发起人',
  `flow_id` int(6) DEFAULT NULL COMMENT '流程id',
  `flow_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '冗余查询',
  `node_id` smallint(6) DEFAULT NULL COMMENT '当前节点ID',
  `child_node_id` int(6) DEFAULT '0' COMMENT '子流程 node_id',
  `instance_record_id` int(6) DEFAULT NULL COMMENT '实例记录ID',
  `instance_parent_node_id` int(6) DEFAULT NULL COMMENT '父流程转入节点ID',
  `circle` tinyint(1) DEFAULT '1' COMMENT '循环次数',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `att_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '附件',
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '流程描述',
  `is_canceled` tinyint(1) DEFAULT NULL COMMENT '是否取消',
  `canceled_time` datetime DEFAULT NULL COMMENT '取消时间',
  `canceled_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '取消原因',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='流程实例';

-- ----------------------------
-- Records of sys_flow_instances
-- ----------------------------
INSERT INTO `sys_flow_instances` VALUES ('170', '0', '掌厅/流程/身份证变更流程-王二亮-2019-12-2', null, '1', '1', null, '50', '0', null, null, '1', '0', null, null, null, null, null, '2019-12-03 17:31:45', '2019-12-03 17:31:45');

-- ----------------------------
-- Table structure for sys_flow_links
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_links`;
CREATE TABLE `sys_flow_links` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `flow_id` int(11) NOT NULL COMMENT '流程id',
  `type` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Condition:表示步骤流转\nRole:当前步骤操作人',
  `node_id` int(11) NOT NULL COMMENT '当前步骤id',
  `next_node_id` int(11) NOT NULL DEFAULT '-1' COMMENT '下一个步骤 Condition -1未指定 0结束 -9上级查找\ntype=Role时为0，不启用',
  `auditor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '审批人 系统自动 指定人员 指定部门 指定角色\ntype=Condition时不启用',
  `expression` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '条件判断表达式\n为1表示true，通过的话直接进入下一步骤',
  `sort` int(11) NOT NULL COMMENT '条件判断顺序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sys_flow_links
-- ----------------------------
INSERT INTO `sys_flow_links` VALUES ('1', '2', 'Sys', '40', '0', '-1002', '', '100', '2019-11-26 16:03:49', '2019-11-26 16:03:49');
INSERT INTO `sys_flow_links` VALUES ('6', '2', 'Condition', '47', '-1', '0', '', '100', '2019-11-27 10:57:34', '2019-11-27 19:32:22');
INSERT INTO `sys_flow_links` VALUES ('7', '2', 'Condition', '37', '38', '0', '', '100', '2019-11-27 11:15:39', '2019-11-27 11:15:39');
INSERT INTO `sys_flow_links` VALUES ('8', '2', 'Condition', '38', '39', '0', '', '100', '2019-11-27 11:15:39', '2019-11-27 11:15:39');
INSERT INTO `sys_flow_links` VALUES ('9', '2', 'Condition', '39', '40', '0', '', '100', '2019-11-27 11:15:39', '2019-11-27 11:15:39');
INSERT INTO `sys_flow_links` VALUES ('10', '2', 'Condition', '40', '47', '0', '', '100', '2019-11-27 11:15:39', '2019-11-27 11:15:39');
INSERT INTO `sys_flow_links` VALUES ('11', '2', 'Sys', '38', '0', '-1001', '', '100', '2019-11-27 11:26:09', '2019-11-27 11:26:09');
INSERT INTO `sys_flow_links` VALUES ('12', '2', 'Sys', '39', '0', '-1002', '', '100', '2019-11-27 11:26:18', '2019-11-27 11:26:18');
INSERT INTO `sys_flow_links` VALUES ('14', '2', 'Dept', '38', '0', '14', '', '100', '2019-11-28 08:49:18', '2019-11-28 08:49:18');
INSERT INTO `sys_flow_links` VALUES ('15', '2', 'Emp', '38', '0', '81', '', '100', '2019-11-28 08:49:18', '2019-11-28 08:49:18');
INSERT INTO `sys_flow_links` VALUES ('16', '2', 'Dept', '37', '0', '19,24,22,21,26,32,23,34,25,30,27,29,20,35,36,33,28,31', '', '100', '2019-11-28 09:16:20', '2019-11-28 09:16:20');
INSERT INTO `sys_flow_links` VALUES ('17', '2', 'Dept', '47', '0', '19,24,22,21,26,32,23,34,25,30,27,29,20,35,36,33,28,31', '', '100', '2019-11-28 09:17:35', '2019-11-28 09:17:35');
INSERT INTO `sys_flow_links` VALUES ('38', '1', 'Sys', '50', '0', '-1001', '', '100', '2019-11-29 11:13:03', '2019-11-29 11:13:03');
INSERT INTO `sys_flow_links` VALUES ('39', '1', 'Sys', '51', '0', '-1002', '', '100', '2019-11-29 11:13:10', '2019-11-29 11:13:10');
INSERT INTO `sys_flow_links` VALUES ('40', '1', 'Dept', '52', '0', '6', '', '100', '2019-11-29 11:13:31', '2019-11-29 11:13:31');
INSERT INTO `sys_flow_links` VALUES ('41', '1', 'Condition', '49', '50', '0', '', '100', '2019-11-29 11:13:40', '2019-11-29 11:13:40');
INSERT INTO `sys_flow_links` VALUES ('42', '1', 'Condition', '50', '51', '0', '', '100', '2019-11-29 11:13:40', '2019-11-29 11:13:40');
INSERT INTO `sys_flow_links` VALUES ('43', '1', 'Condition', '51', '53', '0', '姓名 == 王五 OR (姓名 == 张珊)', '100', '2019-11-29 11:13:40', '2019-12-02 11:09:45');
INSERT INTO `sys_flow_links` VALUES ('44', '1', 'Condition', '51', '52', '0', '姓名 == 李四 OR (姓名 == 赵四)', '100', '2019-11-29 11:13:40', '2019-12-02 11:09:45');
INSERT INTO `sys_flow_links` VALUES ('45', '1', 'Condition', '52', '53', '0', '', '100', '2019-11-29 11:13:40', '2019-11-29 11:13:40');
INSERT INTO `sys_flow_links` VALUES ('46', '1', 'Condition', '53', '-1', '0', '', '100', '2019-11-29 11:13:40', '2019-12-02 10:50:19');
INSERT INTO `sys_flow_links` VALUES ('47', '1', 'Dept', '53', '0', '19,24,12,13,37,22,7,8,21,26,32,6,23,17,34,25,11,18,30,15,5,10,9,16,27,29,20,14,35,36,33,28,31', '', '100', '2019-12-02 15:16:15', '2019-12-02 15:16:15');

-- ----------------------------
-- Table structure for sys_flow_node_conditions
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_node_conditions`;
CREATE TABLE `sys_flow_node_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `flow_id` int(11) NOT NULL COMMENT '流程id',
  `expression_field` varchar(45) COLLATE utf8mb4_bin NOT NULL COMMENT '条件表达式字段名称',
  PRIMARY KEY (`id`),
  KEY `step_id` (`node_id`),
  KEY `workflow_id` (`flow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='步骤判断变量记录';

-- ----------------------------
-- Records of sys_flow_node_conditions
-- ----------------------------
INSERT INTO `sys_flow_node_conditions` VALUES ('1', '33', '1', 'day');
INSERT INTO `sys_flow_node_conditions` VALUES ('3', '73', '3', 'day');
INSERT INTO `sys_flow_node_conditions` VALUES ('4', '51', '1', 'name');

-- ----------------------------
-- Table structure for sys_flow_nodes
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_nodes`;
CREATE TABLE `sys_flow_nodes` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `flow_id` int(6) NOT NULL,
  `node_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '节点名称',
  `type` mediumint(2) DEFAULT NULL COMMENT '节点类型',
  `read_fileds` text COLLATE utf8mb4_unicode_ci COMMENT '可读字段',
  `icon` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图标',
  `write_fields` text COLLATE utf8mb4_unicode_ci COMMENT '可写字段',
  `auto_assigned` tinyint(1) DEFAULT NULL COMMENT '自动选主办人规则0:为不自动选择1：流程发起人2：本部门主管3指定默认人4上级主管领导5. 一级部门主管6. 指定步骤主办人',
  `assigned_role_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '指定角色ids,和用户互斥',
  `assigned_role_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '指定角色,和用户互斥',
  `assigned_user_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '指定审批人,和角色互斥',
  `style` text COLLATE utf8mb4_unicode_ci COMMENT '样式',
  `style_color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '颜色',
  `style_height` smallint(4) DEFAULT NULL COMMENT '高',
  `style_width` smallint(4) DEFAULT NULL COMMENT '宽',
  `position` smallint(4) DEFAULT '1' COMMENT '当前步骤',
  `position_left` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '左侧定位',
  `position_top` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '高度定位',
  `child_id` int(6) DEFAULT NULL COMMENT '子流程id',
  `child_after` tinyint(1) DEFAULT NULL COMMENT '子流程 结束后动作 0结束并更新父流程节点为结束  1结束并返回父流程步骤',
  `child_relation` text COLLATE utf8mb4_unicode_ci COMMENT '[保留功能]父子流程字段映射关系',
  `child_back_node` int(6) DEFAULT NULL COMMENT '子流程结束返回的步骤id',
  `is_sing` tinyint(1) DEFAULT '0' COMMENT '会签选项0禁止会签1允许会签（默认） 2强制会签',
  `out_condition` text COLLATE utf8mb4_unicode_ci COMMENT '转出条件',
  `is_back` tinyint(1) DEFAULT '0' COMMENT '是否允许回退0不允许（默认） 1允许退回上一步2允许退回之前步骤',
  `set_left` smallint(5) DEFAULT '0' COMMENT '左 坐标',
  `set_top` smallint(5) DEFAULT '0' COMMENT '上 坐标',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否软删除， 1已删除',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sys_flow_nodes
-- ----------------------------
INSERT INTO `sys_flow_nodes` VALUES ('35', '2', '新建步骤', null, null, null, null, null, null, null, null, 'width:30px;height:30px;line-height:30px;color:#78a300;left:638px;top:205px;', null, null, null, '1', '638px', '205px', null, null, null, null, '0', null, '0', '0', '0', '0', '2019-11-26 15:40:52', '2019-11-26 15:40:52');
INSERT INTO `sys_flow_nodes` VALUES ('36', '2', '发起流程', null, null, 'icon-play', null, null, null, null, null, 'width:120px;height:30px;line-height:30px;color:#0e76a8;left:713px;top:177px;', '#0e76a8', '30', '120', '1', '713px', '177px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-26 15:44:13', '2019-11-26 15:57:08');
INSERT INTO `sys_flow_nodes` VALUES ('37', '2', '发起流程', null, null, 'icon-play', null, null, null, null, null, 'width:120px;height:30px;line-height:30px;color:#0e76a8;left:258px;top:259px;', '#0e76a8', '30', '120', '0', '258px', '259px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-26 15:56:53', '2019-11-28 09:16:20');
INSERT INTO `sys_flow_nodes` VALUES ('38', '2', '客服部审核', null, null, null, null, null, null, null, null, 'width:px;height:px;line-height:30px;color:#0e76a8;left:532px;top:259px;', '#0e76a8', null, null, '1', '532px', '259px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-26 16:02:14', '2019-11-27 08:53:35');
INSERT INTO `sys_flow_nodes` VALUES ('39', '2', '客服部复审', null, null, null, null, null, null, null, null, 'width:px;height:px;line-height:30px;color:;left:761px;top:259px;', null, null, null, '1', '761px', '259px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-26 16:02:50', '2019-11-26 17:02:30');
INSERT INTO `sys_flow_nodes` VALUES ('40', '2', '客户经理审核', null, null, null, null, null, null, null, null, 'width:px;height:px;line-height:30px;color:;left:1028px;top:259px;', null, null, null, '1', '1028px', '259px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-26 16:03:32', '2019-11-27 08:53:35');
INSERT INTO `sys_flow_nodes` VALUES ('47', '2', '归档', null, null, 'icon-refresh', null, null, null, null, null, 'width:px;height:px;line-height:30px;color:#6a5a8c;left:1032px;top:403px;', '#6a5a8c', null, null, '1', '1032px', '403px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-27 10:56:45', '2019-11-27 10:57:34');
INSERT INTO `sys_flow_nodes` VALUES ('49', '1', '发起流程', null, null, 'icon-play', null, null, null, null, null, 'width:px;height:px;line-height:30px;color:#0e76a8;left:311px;top:210px;', '#0e76a8', null, null, '0', '311px', '210px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-29 09:24:50', '2019-12-03 10:42:34');
INSERT INTO `sys_flow_nodes` VALUES ('50', '1', '主管审核', null, null, null, null, null, null, null, null, 'width:px;height:px;line-height:30px;color:;left:532px;top:210px;', null, null, null, '1', '532px', '210px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-29 09:25:36', '2019-11-29 09:26:17');
INSERT INTO `sys_flow_nodes` VALUES ('51', '1', '部门经理审核', null, null, null, null, null, null, null, null, 'width:px;height:px;line-height:30px;color:;left:741px;top:210px;', null, null, null, '1', '741px', '210px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-29 09:26:23', '2019-11-29 09:28:07');
INSERT INTO `sys_flow_nodes` VALUES ('52', '1', '分管领导审核', null, null, null, null, null, null, null, null, 'width:px;height:px;line-height:30px;color:;left:762px;top:419px;', null, null, null, '1', '762px', '419px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-29 09:26:50', '2019-12-02 10:50:19');
INSERT INTO `sys_flow_nodes` VALUES ('53', '1', '归档', null, null, 'icon-refresh', null, null, null, null, null, 'width:px;height:px;line-height:30px;color:#73716e;left:975px;top:267px;', '#73716e', null, null, '1', '975px', '267px', '0', null, null, '0', '0', null, '0', '0', '0', '0', '2019-11-29 09:27:13', '2019-11-29 09:28:07');

-- ----------------------------
-- Table structure for sys_flow_template_forms
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_template_forms`;
CREATE TABLE `sys_flow_template_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL DEFAULT '0',
  `field` varchar(64) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '表单字段英文名',
  `field_name` varchar(64) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '表单字段中文名',
  `field_type` varchar(64) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '表单字段类型',
  `field_value` text COLLATE utf8mb4_bin COMMENT '表单字段值，select radio checkbox用',
  `field_default_value` text COLLATE utf8mb4_bin COMMENT '表单字段默认值',
  `rules` text COLLATE utf8mb4_bin,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='流程模板表单控件';

-- ----------------------------
-- Records of sys_flow_template_forms
-- ----------------------------
INSERT INTO `sys_flow_template_forms` VALUES ('1', '1', 'day', '请假天数', 'text', null, null, null, '100', '2017-04-25 13:48:16', null);
INSERT INTO `sys_flow_template_forms` VALUES ('2', '1', 'reason', '请假原因', 'textarea', null, null, null, '100', '2017-04-25 07:10:19', '2017-04-25 07:10:19');
INSERT INTO `sys_flow_template_forms` VALUES ('3', '1', 'start_date', '开始日期', 'date', null, null, null, '900', '2017-04-27 16:05:21', '2017-04-27 06:39:51');
INSERT INTO `sys_flow_template_forms` VALUES ('4', '1', 'end_date', '结束日期', 'date', null, null, null, '901', '2017-04-27 06:42:44', '2017-04-27 06:42:44');
INSERT INTO `sys_flow_template_forms` VALUES ('5', '1', 'leave_type', '请假类型', 'select', 0xE79785E581870D0AE5A99AE58187, 0xE79785E58187, null, '50', '2017-04-27 07:12:01', '2017-04-27 07:12:01');
INSERT INTO `sys_flow_template_forms` VALUES ('6', '1', 'sex', '性别', 'radio', 0xE794B70D0AE5A5B30D0AE4BF9DE5AF86, 0xE4BF9DE5AF86, null, '1000', '2017-04-27 08:34:10', '2017-04-27 08:34:10');
INSERT INTO `sys_flow_template_forms` VALUES ('7', '1', 'hobby', '兴趣爱好', 'checkbox', 0xE8B6B3E790830D0AE7AFAEE790830D0AE4B992E4B993E79083, null, null, '1002', '2017-04-27 08:35:28', '2017-04-27 08:35:28');
INSERT INTO `sys_flow_template_forms` VALUES ('8', '1', 'bingli', '病例', 'file', null, null, null, '1200', '2017-04-28 09:48:16', '2017-04-28 09:48:16');
INSERT INTO `sys_flow_template_forms` VALUES ('9', '2', 'address', '证件地址', 'text', null, null, null, '100', '2019-11-29 14:08:03', null);
INSERT INTO `sys_flow_template_forms` VALUES ('10', '2', 'name', '姓名', 'text', null, null, null, '100', '2019-11-29 14:08:16', null);
INSERT INTO `sys_flow_template_forms` VALUES ('11', '2', 'IDCard', '证件号', 'text', null, null, null, '100', '2019-11-29 14:08:36', null);
INSERT INTO `sys_flow_template_forms` VALUES ('12', '2', 'SFZ_ZM', '证件照正面', 'text', null, null, null, '100', '2019-11-29 14:09:21', null);
INSERT INTO `sys_flow_template_forms` VALUES ('13', '2', 'SFZ_FM', '证件照反面', 'text', null, null, null, '100', '2019-11-29 14:09:34', null);
INSERT INTO `sys_flow_template_forms` VALUES ('14', '2', 'sign', '签字', 'text', null, null, null, '100', '2019-11-29 14:10:21', null);

-- ----------------------------
-- Table structure for sys_flow_templates
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_templates`;
CREATE TABLE `sys_flow_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(64) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_name` (`template_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='流程模板';

-- ----------------------------
-- Records of sys_flow_templates
-- ----------------------------
INSERT INTO `sys_flow_templates` VALUES ('1', '请假模板', '2017-04-21 10:36:07', '2017-04-21 10:36:08');
INSERT INTO `sys_flow_templates` VALUES ('2', '证件变更申请模板', '2019-11-29 14:05:48', null);

-- ----------------------------
-- Table structure for sys_flows
-- ----------------------------
DROP TABLE IF EXISTS `sys_flows`;
CREATE TABLE `sys_flows` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `related_table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '相关业务数据表',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '流程标题',
  `flow_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '流程号',
  `groupID` tinyint(2) NOT NULL COMMENT '分组',
  `jsplumb` mediumtext COLLATE utf8mb4_unicode_ci COMMENT '流程视图缓存',
  `sort` mediumint(4) NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0不可用1正常',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '流程描述',
  `template_id` mediumint(4) DEFAULT NULL COMMENT '模板',
  `is_publish` tinyint(1) DEFAULT '0' COMMENT '是否发布',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='过程表';

-- ----------------------------
-- Records of sys_flows
-- ----------------------------
INSERT INTO `sys_flows` VALUES ('1', '', '证件地址变更申请', 'QJLC', '1', '{\"total\":5,\"list\":[{\"id\":49,\"flow_id\":1,\"node_name\":\"\\u53d1\\u8d77\\u6d41\\u7a0b\",\"node_to\":\"50\",\"icon\":\"icon-play\",\"style\":\"width:px;height:px;line-height:30px;color:#0e76a8;left:311px;top:210px;\"},{\"id\":50,\"flow_id\":1,\"node_name\":\"\\u4e3b\\u7ba1\\u5ba1\\u6838\",\"node_to\":\"51\",\"icon\":null,\"style\":\"width:px;height:px;line-height:30px;color:;left:532px;top:210px;\"},{\"id\":51,\"flow_id\":1,\"node_name\":\"\\u90e8\\u95e8\\u7ecf\\u7406\\u5ba1\\u6838\",\"node_to\":\"53,52\",\"icon\":null,\"style\":\"width:px;height:px;line-height:30px;color:;left:741px;top:210px;\"},{\"id\":52,\"flow_id\":1,\"node_name\":\"\\u5206\\u7ba1\\u9886\\u5bfc\\u5ba1\\u6838\",\"node_to\":\"53\",\"icon\":null,\"style\":\"width:px;height:px;line-height:30px;color:;left:762px;top:419px;\"},{\"id\":53,\"flow_id\":1,\"node_name\":\"\\u5f52\\u6863\",\"node_to\":\"\",\"icon\":\"icon-refresh\",\"style\":\"width:px;height:px;line-height:30px;color:#73716e;left:975px;top:267px;\"}]}', '1', '0', '这是测试流程', '2', '1', '2019-11-22 15:23:02', '2019-12-03 10:42:36');
INSERT INTO `sys_flows` VALUES ('2', '', '测试流程', 'ZJDZBG', '1', '{\"total\":5,\"list\":[{\"id\":37,\"flow_id\":2,\"node_name\":\"\\u53d1\\u8d77\\u6d41\\u7a0b\",\"node_to\":\"38\",\"icon\":\"icon-play\",\"style\":\"width:120px;height:30px;line-height:30px;color:#0e76a8;left:258px;top:259px;\"},{\"id\":38,\"flow_id\":2,\"node_name\":\"\\u5ba2\\u670d\\u90e8\\u5ba1\\u6838\",\"node_to\":\"39\",\"icon\":null,\"style\":\"width:px;height:px;line-height:30px;color:#0e76a8;left:532px;top:259px;\"},{\"id\":39,\"flow_id\":2,\"node_name\":\"\\u5ba2\\u670d\\u90e8\\u590d\\u5ba1\",\"node_to\":\"40\",\"icon\":null,\"style\":\"width:px;height:px;line-height:30px;color:;left:761px;top:259px;\"},{\"id\":40,\"flow_id\":2,\"node_name\":\"\\u5ba2\\u6237\\u7ecf\\u7406\\u5ba1\\u6838\",\"node_to\":\"47\",\"icon\":null,\"style\":\"width:px;height:px;line-height:30px;color:;left:1028px;top:259px;\"},{\"id\":47,\"flow_id\":2,\"node_name\":\"\\u5f52\\u6863\",\"node_to\":\"\",\"icon\":\"icon-refresh\",\"style\":\"width:px;height:px;line-height:30px;color:#6a5a8c;left:1032px;top:403px;\"}]}', '1', '0', '证件地址变更申请', '2', '1', '2019-11-25 14:03:43', '2019-11-29 14:47:05');

-- ----------------------------
-- Table structure for sys_menus
-- ----------------------------
DROP TABLE IF EXISTS `sys_menus`;
CREATE TABLE `sys_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unique_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_use` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`unique_name`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sys_menus
-- ----------------------------
INSERT INTO `sys_menus` VALUES ('18', '导航管理', 'sys_nav', '0', '1', 'fa-bars', '/admin/sys_nav', '1', '2018-11-12 17:14:42', '2018-11-12 12:03:44');
INSERT INTO `sys_menus` VALUES ('36', 'RPA任务中心', 'rpa', '0', '2', 'fa-bars', '/admin/sys_rpa', '1', '2018-11-12 17:14:42', '2019-01-04 16:33:16');
INSERT INTO `sys_menus` VALUES ('35', '通知列表', 'sys_message_list', '32', '2', 'fa-circle-o', '/admin/sys_message_list', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('1', '首页', 'sys_dashboard', '0', '0', 'fa-bars', '/admin', '1', '2018-11-12 17:14:42', '2018-11-12 17:14:42');
INSERT INTO `sys_menus` VALUES ('19', '系统管理', 'sys_system', '0', '5', 'fa-bars', '/admin/sys_system', '1', '2018-11-12 17:14:42', '2019-01-07 10:10:15');
INSERT INTO `sys_menus` VALUES ('20', '控制面板', 'sys_board', '0', '6', 'fa-bars', '/admin/sys_board', '1', '2018-11-12 17:14:42', '2019-04-10 14:49:30');
INSERT INTO `sys_menus` VALUES ('21', '菜单管理', 'sys_menu', '18', '0', 'fa-circle-o', '/admin/sys_menu', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('22', '图标管理', 'sys_icon', '18', '1', 'fa-circle-o', '/admin/sys_icon', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('23', '个人中心', 'sys_profile', '19', '0', 'fa-circle-o', '/admin/sys_profile', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('24', '短信记录', 'sys_sms', '19', '1', 'fa-circle-o', '/admin/sys_sms', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('25', '邮件管理', 'sys_mail', '19', '2', 'fa-circle-o', '/admin/sys_mail', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('26', '操作日志', 'sys_logs', '19', '3', 'fa-circle-o', '/admin/sys_logs', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('27', '错误日志', 'sys_error_logs', '19', '4', 'fa-circle-o', '/sys_error_logs', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('28', '用户管理', 'sys_admin', '20', '0', 'fa-circle-o', '/admin/sys_admin', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('29', '角色管理', 'sys_role', '20', '1', 'fa-circle-o', '/admin/sys_role', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('30', '权限模型', 'sys_permission', '20', '2', 'fa-circle-o', '/admin/sys_permission', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('31', '系统设置', 'sys_config', '20', '3', 'fa-circle-o', '/admin/sys_config', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('32', '通知中心', 'sys_notice', '0', '4', 'fa-bars', '/admin/sys_notice', '1', '2018-11-12 17:14:42', '2019-03-07 11:20:15');
INSERT INTO `sys_menus` VALUES ('33', '通知推送', 'sys_message_send', '32', '0', 'fa-circle-o', '/admin/sys_message_send', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('34', '历史通知', 'sys_message_history', '32', '1', 'fa-circle-o', '/admin/sys_message_history', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('37', '任务管理中心', 'rpa_center', '36', '0', 'fa-bars', '/admin/rpa_center', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('38', '朝闻发布', 'rpa_news', '36', '1', 'fa-bars', '/admin/rpa_news', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('39', '失信查询', 'rpa_discredit', '36', '2', 'fa-bars', '/admin/rpa_discredit', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('40', '投资者密码', 'rpa_investorPWD', '36', '3', 'fa-bars', '/admin/rpa_investorPWD', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('41', '客户分组', 'rpa_customerGrouping', '36', '4', 'fa-bars', '/admin/rpa_customerGrouping', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('42', '居间人影像', 'rpa_jjr_image', '36', '5', 'fa-bars', '/admin/rpa_jjr_image', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('43', '居间人回访分配', 'rpa_jjr_distribution', '36', '6', 'fa-bars', '/admin/rpa_jjr_distribution', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('44', '问卷录入', 'rpa_questionnaire', '36', '7', 'fa-bars', '/admin/rpa_questionnaire', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('45', '客户开户视频收集', 'rpa_rtc_collect', '36', '8', 'fa-bars', '/admin/rpa_rtc_collect', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('80', '开户云证件上报', 'rpa_cloud_recognition', '59', '5', 'fa-bars', '/admin/rpa_cloud_recognition', '1', '2019-08-28 10:31:33', '2019-08-28 10:31:33');
INSERT INTO `sys_menus` VALUES ('47', '官网手续费', 'rpa_SettlementFee', '36', '11', 'fa-bars', '/admin/rpa_SettlementFee', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('48', '开户云回访', 'rpa_cloud_distribution', '59', '0', 'fa-bars', '/admin/rpa_cloud_distribution', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('49', '客户资金查询任务', 'rpa_oabreminding', '36', '9', 'fa-bars', '/admin/rpa_oabreminding', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('50', '居间人回访', 'rpa_jjr_records', '59', '1', 'fa-bars', '/admin/rpa_jjr_records', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('51', 'RPA日志', 'rpa_logs', '36', '13', 'fa-bars', '/admin/rpa_logs', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('52', 'RPA数据统计', 'rpa_statistics', '36', '14', 'fa-bars', '/admin/rpa_statistics', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('53', '棉花仓单', 'rpa_cotton', '59', '2', 'fa-bars', '/admin/rpa_cotton', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('59', 'rpa功能中心', 'func', '0', '3', 'fa-bars', '/admin/sys_func', '1', '2018-11-12 17:14:42', '2019-01-07 10:10:25');
INSERT INTO `sys_menus` VALUES ('62', 'API管理', 'sys_api_config', '0', '7', 'fa-bars', '/admin/sys_api_config', '1', '2018-11-12 17:14:42', '2019-04-10 14:49:30');
INSERT INTO `sys_menus` VALUES ('61', 'API列表', 'sys_api', '62', '0', 'fa-circle-o', '/admin/sys_api', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('60', '客户资金查询', 'rpa_customer_funds_search', '59', '3', 'fa-bars', '/admin/rpa_customer_funds_search', '1', '2018-11-12 17:14:42', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('63', '客户PDF下载', 'rpa_downloadPDF', '36', '12', 'fa-bars', '/admin/rpa_downloadPDF', '1', null, '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('71', '设置', 'sys_call_center_setting', '65', '4', 'fa-bars', '/admin/sys_call_center_setting', '1', '2019-05-16 13:41:38', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('72', '聊天记录', 'sys_call_center_record', '65', '0', 'fa-bars', '/admin/sys_call_center_record', '1', '2019-05-20 09:12:18', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('70', '聊天室', 'sys_call_center_chat_room', '65', '1', 'fa-bars', '/admin/sys_call_center_chat_room', '1', '2019-05-16 13:41:38', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('69', '客服管理', 'sys_call_center_manager', '65', '2', 'fa-bars', '/admin/sys_call_center_manager', '1', '2019-05-16 13:41:38', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('68', '模板管理', 'sys_call_center_template', '65', '3', 'fa-bars', '/admin/sys_call_center_template', '1', '2019-05-16 13:41:38', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('65', '客服中心', 'sys_call_center', '0', '8', 'fa-bars', '/admin/sys_call_center', '1', '2019-06-04 15:22:25', '2019-07-23 16:22:02');
INSERT INTO `sys_menus` VALUES ('73', '插件管理', 'sys_plugin', '0', '5', 'fa-bars', '/admin/sys_plugin', '1', '2019-08-02 15:31:41', '2019-08-02 15:32:21');
INSERT INTO `sys_menus` VALUES ('75', '插件管理', 'sys_plugin', '73', '5', 'fa-bars', '/admin/sys_plugin', '1', '2019-08-02 15:33:04', '2019-08-02 15:33:04');
INSERT INTO `sys_menus` VALUES ('77', '插件版本管理', 'sys_plugin_version', '73', '6', 'fa-bars', '/admin/sys_plugin_version', '1', '2019-08-02 15:33:58', '2019-08-02 15:34:17');
INSERT INTO `sys_menus` VALUES ('81', '文档中心', 'sys_document', '0', '6', 'fa-bars', '/admin/sys_document', '1', '2019-09-11 11:04:55', '2019-09-11 11:05:13');
INSERT INTO `sys_menus` VALUES ('82', '流程管理', 'sys_flow', '0', '5', 'fa-circle-o', '/admin/flow', '1', '2019-11-19 15:49:46', '2019-11-25 15:00:06');
INSERT INTO `sys_menus` VALUES ('83', '部门管理', 'sys_dept', '20', '5', 'fa-circle', '/admin/sys_dept', '1', '2019-12-03 10:13:46', '2019-12-03 10:13:46');
