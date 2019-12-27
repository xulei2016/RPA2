/*
Navicat MySQL Data Transfer

Source Server         : 172.16.253.170_3306
Source Server Version : 50724
Source Host           : 172.16.253.170:3306
Source Database       : rpa

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2019-12-27 17:28:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for app_users
-- ----------------------------
DROP TABLE IF EXISTS `app_users`;
CREATE TABLE `app_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表(测试jwt)';

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=266 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for func_archives
-- ----------------------------
DROP TABLE IF EXISTS `func_archives`;
CREATE TABLE `func_archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户名称',
  `zjbh` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '证件编号',
  `zjzh` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '资金账户',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户类型',
  `btype` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '业务类型',
  `credit_list` text COLLATE utf8mb4_unicode_ci COMMENT '失信查询结果',
  `step` int(10) DEFAULT NULL COMMENT '步骤',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for func_archives_files
-- ----------------------------
DROP TABLE IF EXISTS `func_archives_files`;
CREATE TABLE `func_archives_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `archives_id` int(11) DEFAULT NULL COMMENT '主表id',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型 附件/音频',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路劲',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for func_archives_sxstates
-- ----------------------------
DROP TABLE IF EXISTS `func_archives_sxstates`;
CREATE TABLE `func_archives_sxstates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT 'func_archives',
  `name` varchar(50) DEFAULT NULL,
  `idCard` varchar(30) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL COMMENT '客户类型',
  `sfstate` varchar(50) DEFAULT NULL COMMENT '证券业失信',
  `sfsxdate` varchar(50) DEFAULT NULL,
  `cfastate` varchar(50) DEFAULT NULL COMMENT '期货业失信截图',
  `cfasxdate` varchar(50) DEFAULT NULL,
  `xyzgstate` varchar(50) DEFAULT NULL COMMENT '信用中国',
  `xyzgsxdate` varchar(50) DEFAULT NULL,
  `hsstate` varchar(50) DEFAULT NULL COMMENT '恒生黑名单',
  `hshmddate` varchar(50) DEFAULT NULL,
  `created_at` varchar(50) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for func_mediator_flow
-- ----------------------------
DROP TABLE IF EXISTS `func_mediator_flow`;
CREATE TABLE `func_mediator_flow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '主表id',
  `type` tinyint(1) DEFAULT '0' COMMENT '业务类型 0新签 1续签',
  `dept_id` int(11) DEFAULT NULL COMMENT '所属部门',
  `manager_number` int(11) DEFAULT NULL COMMENT '客户经理号',
  `number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '居间编号',
  `sex` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '性别',
  `birthday` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '出生日期',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `edu_background` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '教育背景',
  `sign_img` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '签字照片',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '联系地址',
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮编',
  `profession` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '职业',
  `is_video` tinyint(1) DEFAULT '0',
  `is_exam` tinyint(1) DEFAULT NULL COMMENT '是否通过期货从业资格',
  `is_answer` tinyint(1) DEFAULT NULL COMMENT '是否通过答题',
  `exam_img` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '从业资格考试照片',
  `rate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '居间比例',
  `jr_rate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '金融产品比例',
  `xy_date_begin` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '协议开始日期',
  `xy_date_end` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '协议结束日期',
  `xy_location` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '协议所在地',
  `sfz_zm_img` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证正面照',
  `sfz_fm_img` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证反面照',
  `sfz_sc_img` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手持身份证照片',
  `sfz_date_end` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证到期日',
  `sfz_address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '证件地址',
  `bank_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '银行卡号',
  `bank_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '开户银行',
  `bank_branch` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支行',
  `bank_username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '开户人',
  `bank_img` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '银行卡照片',
  `from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '来源',
  `step` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_check` tinyint(1) DEFAULT '0' COMMENT '是否审核',
  `check_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '审核时间',
  `is_sure` tinyint(1) DEFAULT '0' COMMENT '是否确认居间比例',
  `sure_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '确认比例时间',
  `is_handle` tinyint(1) DEFAULT '0' COMMENT '是否办理',
  `handle_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '办理时间',
  `is_review` tinyint(1) DEFAULT '0' COMMENT '是否需要加入回访分配 0不需要 1需要',
  `is_manager_agree` tinyint(1) DEFAULT NULL COMMENT '客户经理是否同意续签',
  `agree_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '同意续签时间',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='居间人业务表';

-- ----------------------------
-- Table structure for func_mediator_greylists
-- ----------------------------
DROP TABLE IF EXISTS `func_mediator_greylists`;
CREATE TABLE `func_mediator_greylists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '主表id',
  `dept_id` int(11) DEFAULT NULL COMMENT '所属部门',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='居间人灰名单表';

-- ----------------------------
-- Table structure for func_mediator_info
-- ----------------------------
DROP TABLE IF EXISTS `func_mediator_info`;
CREATE TABLE `func_mediator_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '居间名称',
  `zjbh` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '证件编号',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号',
  `open_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '开户日期',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态   0冻结 1正常',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='居间人主表';

-- ----------------------------
-- Table structure for func_mediator_potic
-- ----------------------------
DROP TABLE IF EXISTS `func_mediator_potic`;
CREATE TABLE `func_mediator_potic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '题目',
  `optionA` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '选项A',
  `optionB` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '选项B',
  `optionC` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '选项C',
  `optionD` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '选项D',
  `answer` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '答案',
  `order` int(5) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='居间试题表';

-- ----------------------------
-- Table structure for func_mediator_step
-- ----------------------------
DROP TABLE IF EXISTS `func_mediator_step`;
CREATE TABLE `func_mediator_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(6) DEFAULT NULL COMMENT '代码',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'url',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='居间人流程对照表';

-- ----------------------------
-- Table structure for func_risk_degrees
-- ----------------------------
DROP TABLE IF EXISTS `func_risk_degrees`;
CREATE TABLE `func_risk_degrees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `khh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客户号',
  `zjzh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资金账号',
  `khxm` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `yyb` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '营业部',
  `rq` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '日期',
  `sxf` decimal(12,4) DEFAULT NULL COMMENT '手续费',
  `sxf_jys` decimal(12,4) DEFAULT NULL COMMENT '交易所手续费',
  `brjc` decimal(14,2) DEFAULT NULL COMMENT '本日结存',
  `bzj` decimal(12,2) DEFAULT NULL COMMENT '保证金占用',
  `bzj_jys` decimal(12,2) DEFAULT NULL COMMENT '交易所保证金',
  `bzj_rate` decimal(6,4) DEFAULT NULL COMMENT '保证金风险度',
  `jys_rate` decimal(6,4) DEFAULT NULL COMMENT '交易所风险度',
  `pz1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种1',
  `pz1_rate` decimal(6,4) DEFAULT NULL COMMENT '比例1',
  `pz2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种2',
  `pz2_rate` decimal(6,4) DEFAULT NULL COMMENT '比例2',
  `pz3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种3',
  `pz3_rate` decimal(6,4) DEFAULT NULL COMMENT '比例3',
  `khjl` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户经理',
  `pz1_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种1',
  `pz1_c_rate` decimal(6,4) DEFAULT NULL COMMENT '比例1',
  `pz2_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种2',
  `pz2_c_rate` decimal(6,4) DEFAULT NULL COMMENT '比例2',
  `pz3_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种3',
  `pz3_c_rate` decimal(6,4) DEFAULT NULL COMMENT '比例3',
  `exp1` decimal(6,4) DEFAULT NULL COMMENT '敞口',
  `exp2` decimal(6,4) DEFAULT NULL COMMENT '敞口',
  `exp3` decimal(6,4) DEFAULT NULL COMMENT '敞口',
  `exp1_c` decimal(6,4) DEFAULT NULL COMMENT '敞口',
  `exp2_c` decimal(6,4) DEFAULT NULL COMMENT '敞口',
  `exp3_c` decimal(6,4) DEFAULT NULL COMMENT '敞口',
  `jgbz` tinyint(1) DEFAULT NULL COMMENT '0普通 1机构 2自营',
  PRIMARY KEY (`id`),
  KEY `zjzh` (`zjzh`),
  KEY `khxm` (`khxm`) USING BTREE,
  KEY `rq` (`rq`),
  KEY `pz1_rate` (`pz1_rate`),
  KEY `exp1` (`exp1`),
  KEY `bzj_rate` (`bzj_rate`),
  KEY `jys_rate` (`jys_rate`),
  KEY `jgbz` (`jgbz`)
) ENGINE=MyISAM AUTO_INCREMENT=331374 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='风险度';

-- ----------------------------
-- Table structure for func_risk_degrees_copy
-- ----------------------------
DROP TABLE IF EXISTS `func_risk_degrees_copy`;
CREATE TABLE `func_risk_degrees_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `khh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客户号',
  `zjzh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资金账号',
  `khxm` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `yyb` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '营业部',
  `rq` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '日期',
  `sxf` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手续费',
  `sxf_jys` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '交易所手续费',
  `brjc` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '本日结存',
  `bzj` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '保证金占用',
  `bzj_jys` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '交易所保证金',
  `bzj_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '保证金风险度',
  `jys_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '交易所风险度',
  `pz1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种1',
  `pz1_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比例1',
  `pz2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种2',
  `pz2_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比例2',
  `pz3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种3',
  `pz3_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比例3',
  `khjl` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户经理',
  `pz1_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种1',
  `pz1_c_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比例1',
  `pz2_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种2',
  `pz2_c_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比例2',
  `pz3_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '品种3',
  `pz3_c_rate` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比例3',
  `exp1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '敞口',
  `exp2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '敞口',
  `exp3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '敞口',
  `exp1_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '敞口',
  `exp2_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '敞口',
  `exp3_c` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '敞口',
  `jgbz` tinyint(1) DEFAULT NULL COMMENT '0普通 1机构 2自营',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47998 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='风险度';

-- ----------------------------
-- Table structure for func_risk_khs
-- ----------------------------
DROP TABLE IF EXISTS `func_risk_khs`;
CREATE TABLE `func_risk_khs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `khh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客户号',
  `zjzh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资金账号',
  `khxm` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `yyb` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '营业部',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24476 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='风险度和持仓集中度 客户表';

-- ----------------------------
-- Table structure for func_risk_khs_copy
-- ----------------------------
DROP TABLE IF EXISTS `func_risk_khs_copy`;
CREATE TABLE `func_risk_khs_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `khh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客户号',
  `zjzh` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资金账号',
  `khxm` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `yyb` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '营业部',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24476 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='风险度和持仓集中度 客户表';

-- ----------------------------
-- Table structure for func_risk_positions
-- ----------------------------
DROP TABLE IF EXISTS `func_risk_positions`;
CREATE TABLE `func_risk_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kh_id` int(11) NOT NULL COMMENT '客户id',
  `rq` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '日期',
  `jys` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '交易所',
  `hypz` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '合约品种',
  `hydm` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '合约代码',
  `wtlb` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '委托类别  1买 2卖',
  `tzlb` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '投资类别  0投机 1套保',
  `ccsl` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '持仓数量',
  `ccjg` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '持仓价格',
  `bzj` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '保证金',
  `bzj_jys` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '交易所保证金',
  `jys_rate` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54830 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='持仓集中度';

-- ----------------------------
-- Table structure for func_risk_positions_copy
-- ----------------------------
DROP TABLE IF EXISTS `func_risk_positions_copy`;
CREATE TABLE `func_risk_positions_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kh_id` int(11) NOT NULL COMMENT '客户id',
  `rq` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '日期',
  `jys` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '交易所',
  `hypz` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '合约品种',
  `hydm` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '合约代码',
  `wtlb` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '委托类别  1买 2卖',
  `tzlb` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '投资类别  0投机 1套保',
  `ccsl` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '持仓数量',
  `ccjg` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '持仓价格',
  `bzj` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '保证金',
  `bzj_jys` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '交易所保证金',
  `jys_rate` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54830 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='持仓集中度';

-- ----------------------------
-- Table structure for func_risk_querys
-- ----------------------------
DROP TABLE IF EXISTS `func_risk_querys`;
CREATE TABLE `func_risk_querys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rq` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '查询日期',
  `created_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1 查询  2 成功 3失败',
  `updated_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` int(10) unsigned NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for oauth_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for oauth_auth_codes
-- ----------------------------
DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for oauth_clients
-- ----------------------------
DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for oauth_personal_access_clients
-- ----------------------------
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for oauth_refresh_tokens
-- ----------------------------
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_accesstokens
-- ----------------------------
DROP TABLE IF EXISTS `rpa_accesstokens`;
CREATE TABLE `rpa_accesstokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` text,
  `updated_at` datetime DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `refresh_token` text,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1012 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_address_recognitions
-- ----------------------------
DROP TABLE IF EXISTS `rpa_address_recognitions`;
CREATE TABLE `rpa_address_recognitions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zjzh` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资金账号',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '识别的证件地址',
  `address_deep` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '深度识别地址',
  `address_final` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '最终地址',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 -1打回 0未处理 1审核通过 2复核通过 4-7rpa任务步骤',
  `rpa_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未运行 1已运行',
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注信息',
  `task_time` datetime DEFAULT NULL,
  `check` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '审核人',
  `check_time` datetime DEFAULT NULL COMMENT '审核时间',
  `review` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '复核人',
  `review_time` datetime DEFAULT NULL COMMENT '复核时间',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3039 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_api_ips
-- ----------------------------
DROP TABLE IF EXISTS `rpa_api_ips`;
CREATE TABLE `rpa_api_ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `black_list` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `white_list` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` int(1) DEFAULT '1',
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_api_logs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_api_logs`;
CREATE TABLE `rpa_api_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `param` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `return` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1660173 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_capitalrefers
-- ----------------------------
DROP TABLE IF EXISTS `rpa_capitalrefers`;
CREATE TABLE `rpa_capitalrefers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `exfund` bigint(20) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_captcha
-- ----------------------------
DROP TABLE IF EXISTS `rpa_captcha`;
CREATE TABLE `rpa_captcha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号',
  `code` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '验证码',
  `count` int(5) DEFAULT NULL COMMENT '发送次数',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '业务类型',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_clock_list
-- ----------------------------
DROP TABLE IF EXISTS `rpa_clock_list`;
CREATE TABLE `rpa_clock_list` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(50) DEFAULT NULL COMMENT '主服务器序列',
  `CARD_IM` tinyint(1) DEFAULT NULL COMMENT '立即任务线程状态',
  `CARD_TM` tinyint(1) DEFAULT NULL COMMENT '定时任务线程状态',
  `CARD_EX` tinyint(1) DEFAULT NULL COMMENT '任务调度线程状态',
  `CARD_RT` tinyint(1) DEFAULT NULL COMMENT '发布任务线程状态',
  `CPU_sum` varchar(10) DEFAULT NULL COMMENT 'cpu使用率',
  `Memory_mem` varchar(10) DEFAULT NULL COMMENT '内存使用率',
  `Disks_mem` varchar(20) DEFAULT NULL COMMENT '硬盘使用率',
  `Process_p` tinyint(1) DEFAULT NULL COMMENT '检测器1线程存活',
  `Process_e` tinyint(1) DEFAULT NULL COMMENT '检测器2线程存活',
  `sysparameter` text,
  `created_at` varchar(20) NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=776531 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_cotton_batchs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_cotton_batchs`;
CREATE TABLE `rpa_cotton_batchs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pihao` varchar(15) DEFAULT '' COMMENT '批号',
  `package` varchar(15) DEFAULT NULL COMMENT '包数',
  `level` varchar(15) DEFAULT '' COMMENT '等级',
  `eid` int(11) DEFAULT NULL COMMENT '仓单号',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0正常1被替包',
  `time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=260 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_cotton_batchs_tmps
-- ----------------------------
DROP TABLE IF EXISTS `rpa_cotton_batchs_tmps`;
CREATE TABLE `rpa_cotton_batchs_tmps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pihao` varchar(15) DEFAULT '' COMMENT '批号',
  `package` varchar(15) DEFAULT NULL COMMENT '包数',
  `level` varchar(15) DEFAULT '' COMMENT '等级',
  `eid` int(11) DEFAULT NULL COMMENT '仓单号',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_cotton_entrys
-- ----------------------------
DROP TABLE IF EXISTS `rpa_cotton_entrys`;
CREATE TABLE `rpa_cotton_entrys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sqrkck` varchar(50) DEFAULT NULL COMMENT '申请入库仓库',
  `ckdm` varchar(50) DEFAULT NULL COMMENT '仓库代码',
  `hyh` varchar(50) DEFAULT NULL COMMENT '会员号',
  `hymc` varchar(50) DEFAULT NULL COMMENT '会员名称',
  `lxr` varchar(50) DEFAULT NULL COMMENT '联系人',
  `lxdh` varchar(50) DEFAULT NULL COMMENT '联系电话',
  `khbm` varchar(50) DEFAULT NULL COMMENT '客户编码',
  `khmc` varchar(50) DEFAULT NULL COMMENT '客户名称',
  `khlxr` varchar(50) DEFAULT NULL COMMENT '客户联系人',
  `khlxdh` varchar(20) DEFAULT NULL COMMENT '客户联系电话',
  `scnd` varchar(20) DEFAULT NULL COMMENT '生产年度',
  `spcdsf` varchar(30) DEFAULT NULL COMMENT '商品产地省份',
  `spcdzz` varchar(200) DEFAULT NULL COMMENT '商品产地地址',
  `ybsl` varchar(20) DEFAULT NULL COMMENT '预报数量',
  `bzgg` varchar(50) DEFAULT NULL COMMENT '包装规格',
  `jgsjksnf` varchar(4) DEFAULT NULL COMMENT '加工时间开始年份',
  `jgsjksyf` varchar(2) DEFAULT NULL,
  `jgsjjsnf` varchar(4) DEFAULT NULL COMMENT '加工时间结束年份',
  `jgsjjsyf` varchar(2) DEFAULT NULL COMMENT '加工时间结束月份',
  `jgdwdm` varchar(20) DEFAULT NULL COMMENT '加工单位代码',
  `jgdw` varchar(255) DEFAULT NULL COMMENT '加工单位',
  `sfwbscd` varchar(20) DEFAULT NULL COMMENT '是否为报税仓单',
  `file_address` varchar(255) DEFAULT NULL COMMENT '源文件路径',
  `ip` varchar(25) DEFAULT NULL COMMENT 'ip',
  `saveDate` varchar(50) DEFAULT '' COMMENT '归档时间',
  `date` varchar(25) DEFAULT NULL COMMENT '签章日期',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `operator` varchar(50) DEFAULT NULL COMMENT '操作人',
  `version` varchar(50) DEFAULT NULL COMMENT '版本号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_cotton_entrys_tmps
-- ----------------------------
DROP TABLE IF EXISTS `rpa_cotton_entrys_tmps`;
CREATE TABLE `rpa_cotton_entrys_tmps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sqrkck` varchar(50) DEFAULT NULL COMMENT '申请入库仓库',
  `ckdm` varchar(50) DEFAULT NULL COMMENT '仓库代码',
  `hyh` varchar(50) DEFAULT NULL COMMENT '会员号',
  `hymc` varchar(50) DEFAULT NULL COMMENT '会员名称',
  `lxr` varchar(50) DEFAULT NULL COMMENT '联系人',
  `lxdh` varchar(50) DEFAULT NULL COMMENT '联系电话',
  `khbm` varchar(50) DEFAULT NULL COMMENT '客户编码',
  `khmc` varchar(50) DEFAULT NULL COMMENT '客户名称',
  `khlxr` varchar(50) DEFAULT NULL COMMENT '客户联系人',
  `khlxdh` varchar(20) DEFAULT NULL COMMENT '客户联系电话',
  `scnd` varchar(20) DEFAULT NULL COMMENT '生产年度',
  `spcdsf` varchar(30) DEFAULT NULL COMMENT '商品产地省份',
  `spcdzz` varchar(200) DEFAULT NULL COMMENT '商品产地地址',
  `ybsl` varchar(20) DEFAULT NULL COMMENT '预报数量',
  `bzgg` varchar(50) DEFAULT NULL COMMENT '包装规格',
  `jgsjksnf` varchar(4) DEFAULT NULL COMMENT '加工时间开始年份',
  `jgsjksyf` varchar(2) DEFAULT NULL,
  `jgsjjsnf` varchar(4) DEFAULT NULL COMMENT '加工时间结束年份',
  `jgsjjsyf` varchar(2) DEFAULT NULL COMMENT '加工时间结束月份',
  `jgdwdm` varchar(20) DEFAULT NULL COMMENT '加工单位代码',
  `jgdw` varchar(255) DEFAULT NULL COMMENT '加工单位',
  `sfwbscd` varchar(20) DEFAULT NULL COMMENT '是否为报税仓单',
  `file_address` varchar(255) DEFAULT NULL COMMENT '源文件路径',
  `ip` varchar(25) DEFAULT NULL COMMENT 'ip',
  `date` varchar(25) DEFAULT NULL COMMENT '签章日期',
  `remark` text COMMENT '备注',
  `state` tinyint(1) DEFAULT '0' COMMENT '状态 0未解析 1解析成功 2解析失败',
  `saveDate` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `operator` varchar(50) DEFAULT NULL COMMENT '操作人',
  `version` varchar(50) DEFAULT NULL COMMENT '版本号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_crm_flows
-- ----------------------------
DROP TABLE IF EXISTS `rpa_crm_flows`;
CREATE TABLE `rpa_crm_flows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `number` varchar(100) NOT NULL,
  `work_id` int(11) DEFAULT NULL,
  `file_id` int(11) NOT NULL,
  `type` varchar(5) NOT NULL,
  `operator` varchar(50) DEFAULT NULL,
  `created_at` varchar(50) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2108 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_crm_lcjd
-- ----------------------------
DROP TABLE IF EXISTS `rpa_crm_lcjd`;
CREATE TABLE `rpa_crm_lcjd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 DEFAULT '' COMMENT '用户名',
  `tel` varchar(30) CHARACTER SET utf8 DEFAULT '' COMMENT '手机号',
  `lcname` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '流程标题',
  `lcid` int(11) DEFAULT NULL COMMENT '流程id',
  `status` int(1) DEFAULT '0' COMMENT '发送状态，0未发送1已发送',
  `created_at` varchar(50) CHARACTER SET utf8 DEFAULT '' COMMENT '添加时间',
  `updated_at` varchar(50) CHARACTER SET utf8 DEFAULT '' COMMENT '更新时间',
  `oid` int(11) DEFAULT NULL COMMENT 'oracle的id',
  `rq` varchar(8) DEFAULT NULL,
  `userid` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19966 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_crm_lcztcx
-- ----------------------------
DROP TABLE IF EXISTS `rpa_crm_lcztcx`;
CREATE TABLE `rpa_crm_lcztcx` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(255) DEFAULT NULL COMMENT '表名',
  `instid` int(11) DEFAULT NULL COMMENT '所属流程实例ID',
  `state` int(1) DEFAULT NULL COMMENT '状态，1 处理中3 已终止，4 已完成',
  `initiator` varchar(100) DEFAULT NULL COMMENT '启动用户',
  `init_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '启动日期',
  `subject` text COMMENT '流程标题',
  `step_id` int(3) DEFAULT NULL COMMENT '步骤ID',
  `last_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '最后处理时间',
  `created_at` varchar(30) DEFAULT NULL,
  `updated_at` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46530 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_customer_infos
-- ----------------------------
DROP TABLE IF EXISTS `rpa_customer_infos`;
CREATE TABLE `rpa_customer_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fundAccount` varchar(50) NOT NULL DEFAULT '' COMMENT '//资金账号',
  `sign` varchar(100) DEFAULT '' COMMENT '//签名照片',
  `sfz_zm` varchar(100) DEFAULT '' COMMENT '//身份证正面',
  `sfz_fm` varchar(100) DEFAULT '' COMMENT '//身份证反面',
  `head_zm` varchar(100) DEFAULT '' COMMENT '//头部正面照',
  `operator` varchar(50) DEFAULT '' COMMENT '//操作人',
  `created_at` varchar(50) NOT NULL DEFAULT '' COMMENT '//创建时间',
  `updated_at` varchar(50) NOT NULL DEFAULT '' COMMENT '//更新时间',
  `state` int(11) DEFAULT NULL COMMENT '是否从账户管理系统插入',
  `runstatus` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fundAccount` (`fundAccount`) USING BTREE COMMENT '//资金账号唯一'
) ENGINE=InnoDB AUTO_INCREMENT=8159 DEFAULT CHARSET=utf8mb4 COMMENT='//客户信息表';

-- ----------------------------
-- Table structure for rpa_customer_jkzx
-- ----------------------------
DROP TABLE IF EXISTS `rpa_customer_jkzx`;
CREATE TABLE `rpa_customer_jkzx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `account` int(10) NOT NULL DEFAULT '0',
  `pwd` varchar(10) NOT NULL DEFAULT '',
  `tel` varchar(11) NOT NULL DEFAULT '',
  `fzjg` varchar(15) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT '0',
  `opentime` int(15) NOT NULL DEFAULT '1' COMMENT '是否有交易编码 0没有1有',
  `inputtime` int(15) NOT NULL DEFAULT '0',
  `content` text,
  `has_jybm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有交易编码 0没有1有',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48330 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_customer_jkzx_copy
-- ----------------------------
DROP TABLE IF EXISTS `rpa_customer_jkzx_copy`;
CREATE TABLE `rpa_customer_jkzx_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `account` int(10) NOT NULL DEFAULT '0',
  `pwd` varchar(10) NOT NULL DEFAULT '',
  `tel` varchar(11) NOT NULL DEFAULT '',
  `fzjg` varchar(15) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT '0',
  `opentime` int(15) NOT NULL DEFAULT '1' COMMENT '是否有交易编码 0没有1有',
  `inputtime` int(15) NOT NULL DEFAULT '0',
  `content` text,
  `has_jybm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有交易编码 0没有1有',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44704 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_customer_manager
-- ----------------------------
DROP TABLE IF EXISTS `rpa_customer_manager`;
CREATE TABLE `rpa_customer_manager` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '客户姓名',
  `idCard` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `is_visit` int(1) DEFAULT '0' COMMENT '是否回访 1已回访',
  `customerNum` varchar(10) DEFAULT NULL COMMENT '客户经理工号',
  `fundsNum` varchar(20) DEFAULT NULL COMMENT '资金账号',
  `message` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `creater` varchar(20) DEFAULT NULL COMMENT '操作管理员名称',
  `add_time` varchar(20) DEFAULT NULL COMMENT '添加时间',
  `visit_message` varchar(255) DEFAULT NULL COMMENT '回访记录',
  `visit_time` varchar(20) DEFAULT NULL COMMENT '回访时间',
  `jjrNum` varchar(10) DEFAULT NULL COMMENT '居间人号',
  `jjrName` varchar(25) DEFAULT NULL COMMENT '居间人名称',
  `yybName` varchar(25) DEFAULT NULL COMMENT '营业部名称',
  `yybNum` varchar(10) DEFAULT NULL,
  `customerManagerName` varchar(25) DEFAULT NULL COMMENT '经理人名称',
  `special` varchar(20) DEFAULT NULL COMMENT '是否二次股指客户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36597 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_customer_videos
-- ----------------------------
DROP TABLE IF EXISTS `rpa_customer_videos`;
CREATE TABLE `rpa_customer_videos` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `yyb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jlr_name` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '经理人姓名',
  `jlr_bh` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '经理人编号',
  `customer_name` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户姓名',
  `customer_sfzh` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户身份证号',
  `customer_zjzh` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户资金账号',
  `btype` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '业务类型',
  `jsondata` text COLLATE utf8mb4_unicode_ci COMMENT 'json-视频文件：路径、MD5、文件名等信息',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未处理 1、已归档 2、打回',
  `reason` text COLLATE utf8mb4_unicode_ci COMMENT '打回原因',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_dtu_sms
-- ----------------------------
DROP TABLE IF EXISTS `rpa_dtu_sms`;
CREATE TABLE `rpa_dtu_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(100) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `created_at` varchar(50) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_filebinarys
-- ----------------------------
DROP TABLE IF EXISTS `rpa_filebinarys`;
CREATE TABLE `rpa_filebinarys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filebinary` longtext COLLATE utf8mb4_unicode_ci COMMENT '图片二进制',
  `codetype` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=284 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_flows
-- ----------------------------
DROP TABLE IF EXISTS `rpa_flows`;
CREATE TABLE `rpa_flows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '流程标题',
  `fundAccount` varchar(20) NOT NULL DEFAULT '' COMMENT '资金账号',
  `flownum` varchar(20) NOT NULL DEFAULT '' COMMENT '流程编号',
  `username` varchar(100) DEFAULT '' COMMENT '客户姓名',
  `file_id` varchar(20) NOT NULL DEFAULT '' COMMENT '图片id',
  `operator` varchar(50) NOT NULL DEFAULT '' COMMENT '操作人',
  `created_at` varchar(50) NOT NULL DEFAULT '' COMMENT '创建时间',
  `updated_at` varchar(50) NOT NULL DEFAULT '' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4113 DEFAULT CHARSET=utf8mb4 COMMENT='流程表';

-- ----------------------------
-- Table structure for rpa_fzkh_steps
-- ----------------------------
DROP TABLE IF EXISTS `rpa_fzkh_steps`;
CREATE TABLE `rpa_fzkh_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL COMMENT '对应khs的id',
  `uid` int(11) DEFAULT NULL COMMENT '对应fzkhs的id',
  `step` varchar(50) DEFAULT NULL COMMENT '步骤',
  `states` varchar(50) DEFAULT NULL COMMENT '状态,0失败，1成功',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_at` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_fzkhs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_fzkhs`;
CREATE TABLE `rpa_fzkhs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `businAccount` varchar(10) DEFAULT NULL COMMENT '资金账号',
  `time` datetime NOT NULL COMMENT '录入时间',
  `sendMessage` varchar(20) DEFAULT NULL,
  `clientName` varchar(255) DEFAULT NULL COMMENT '姓名',
  `idNumber` varchar(18) NOT NULL COMMENT '身份证号码',
  `provinceCode` varchar(30) DEFAULT NULL,
  `cityCode` varchar(30) DEFAULT NULL,
  `contactAddress` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `postCode` varchar(7) DEFAULT NULL COMMENT '邮政编码',
  `contactNumber` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `email` varchar(30) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `clientId` varchar(10) DEFAULT NULL,
  `sdxkhId` int(2) DEFAULT NULL,
  `fxcpId` int(2) DEFAULT NULL,
  `khstId` int(2) DEFAULT NULL,
  `shangBaoId` int(2) DEFAULT NULL,
  `chaJybmId` int(2) DEFAULT NULL,
  `kaiQiQuanId` int(2) DEFAULT NULL,
  `ufRuJinId` int(2) DEFAULT NULL,
  `sendMessId` int(2) DEFAULT NULL,
  `server` varchar(20) DEFAULT NULL,
  `limittime` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `tid` int(20) DEFAULT NULL,
  `isold` varchar(2) DEFAULT NULL,
  `system` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1532 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_hadmy_version
-- ----------------------------
DROP TABLE IF EXISTS `rpa_hadmy_version`;
CREATE TABLE `rpa_hadmy_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '版本号',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '路劲',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '0禁用 1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_hfsers
-- ----------------------------
DROP TABLE IF EXISTS `rpa_hfsers`;
CREATE TABLE `rpa_hfsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hyh` varchar(50) DEFAULT NULL COMMENT '会员号',
  `khbm` varchar(50) DEFAULT NULL COMMENT '客户编码',
  `pz` varchar(50) DEFAULT NULL COMMENT '品种',
  `cdbh` varchar(50) DEFAULT NULL COMMENT '仓单编号',
  `year` varchar(50) DEFAULT NULL COMMENT '年度',
  `level` varchar(50) DEFAULT NULL COMMENT '等级',
  `type` varchar(50) DEFAULT NULL COMMENT '类别',
  `ck` varchar(50) DEFAULT NULL COMMENT '仓库',
  `hw` varchar(50) DEFAULT NULL COMMENT '货位',
  `adress` varchar(50) DEFAULT NULL COMMENT '产地',
  `zl` varchar(50) DEFAULT NULL COMMENT '重量',
  `cdnum` varchar(50) DEFAULT NULL COMMENT '仓单数量',
  `kycd` varchar(50) DEFAULT NULL COMMENT '可用仓单',
  `zydj` varchar(50) DEFAULT NULL COMMENT '质押登记/冻结数量',
  `cdsl` varchar(50) DEFAULT NULL COMMENT '充抵数量',
  `zdsl` varchar(50) DEFAULT NULL COMMENT '折抵数量',
  `updatetime` varchar(50) DEFAULT NULL,
  `sold` int(2) DEFAULT NULL COMMENT '是否过期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=616 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_idcard_logs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_idcard_logs`;
CREATE TABLE `rpa_idcard_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_ip` varchar(20) DEFAULT '',
  `log_img` varchar(255) DEFAULT '',
  `log_res` text,
  `log_time` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12787 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_immedtasks
-- ----------------------------
DROP TABLE IF EXISTS `rpa_immedtasks`;
CREATE TABLE `rpa_immedtasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '程序名称（没有后缀）',
  `state` varchar(255) DEFAULT NULL COMMENT '状态',
  `jsondata` text COMMENT 'json格式的条件',
  `updatetime` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `limittime` datetime DEFAULT NULL COMMENT '服务器',
  `server` varchar(10) DEFAULT NULL COMMENT '服务器',
  `remarks` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105228 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_innersyspwds
-- ----------------------------
DROP TABLE IF EXISTS `rpa_innersyspwds`;
CREATE TABLE `rpa_innersyspwds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `change_time` datetime NOT NULL COMMENT '修改时间',
  `current_password` varchar(255) NOT NULL COMMENT '密码',
  `system_name` varchar(255) NOT NULL COMMENT '系统名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_jjrvis
-- ----------------------------
DROP TABLE IF EXISTS `rpa_jjrvis`;
CREATE TABLE `rpa_jjrvis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `revisit` varchar(255) DEFAULT NULL,
  `deptname` varchar(255) DEFAULT NULL,
  `mediatorname` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `manager_name` varchar(255) DEFAULT NULL,
  `managerNo` int(11) DEFAULT NULL,
  `rate` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `open_date` varchar(255) DEFAULT NULL,
  `completed_date` varchar(255) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `status` int(1) DEFAULT '0' COMMENT '0未回访 1已回访',
  `number` varchar(255) DEFAULT NULL,
  `reason` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `bz` text CHARACTER SET utf8mb4,
  `khyj` text CHARACTER SET utf8mb4,
  `review_date` varchar(50) DEFAULT NULL COMMENT '回访时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3928 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_jjrvis_copy
-- ----------------------------
DROP TABLE IF EXISTS `rpa_jjrvis_copy`;
CREATE TABLE `rpa_jjrvis_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `revisit` varchar(255) DEFAULT NULL,
  `deptname` varchar(255) DEFAULT NULL,
  `mediatorname` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `manager_name` varchar(255) DEFAULT NULL,
  `managerNo` int(11) DEFAULT NULL,
  `rate` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `open_date` varchar(255) DEFAULT NULL,
  `completed_date` varchar(255) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `status` int(1) DEFAULT '0' COMMENT '0未回访 1已回访',
  `number` varchar(255) DEFAULT NULL,
  `reason` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `bz` text CHARACTER SET utf8mb4,
  `khyj` text CHARACTER SET utf8mb4,
  `review_date` varchar(50) DEFAULT NULL COMMENT '回访时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1805 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_jkzxaccounts
-- ----------------------------
DROP TABLE IF EXISTS `rpa_jkzxaccounts`;
CREATE TABLE `rpa_jkzxaccounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `last_day` varchar(25) DEFAULT NULL COMMENT '资金账号',
  `last_month` varchar(25) DEFAULT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `ispic` varchar(10) DEFAULT NULL,
  `khjybm` varchar(255) DEFAULT NULL,
  `jt_time` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `qhname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4985 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_jkzxbills
-- ----------------------------
DROP TABLE IF EXISTS `rpa_jkzxbills`;
CREATE TABLE `rpa_jkzxbills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) DEFAULT NULL COMMENT '账号',
  `nbqqzjzh` varchar(25) DEFAULT NULL COMMENT '客户期货期权内部资金账户',
  `jymonth` varchar(11) DEFAULT NULL COMMENT '交易月份',
  `name` varchar(15) DEFAULT NULL COMMENT '客户名称',
  `cxtime` varchar(30) DEFAULT NULL COMMENT '查询时间',
  `qhname` varchar(50) DEFAULT NULL COMMENT '期货公司名称',
  `nbzqzjzh` varchar(25) DEFAULT NULL COMMENT '客户证券现货内部资金账户',
  `jyrq` varchar(30) NOT NULL COMMENT '交易日期',
  `hy` varchar(100) DEFAULT NULL COMMENT '合约',
  `cjxh` varchar(100) NOT NULL COMMENT '成交序号',
  `cjsj` varchar(25) DEFAULT NULL COMMENT '成交时间',
  `mm` varchar(2) DEFAULT NULL COMMENT '买/卖',
  `tjtb` varchar(4) DEFAULT NULL COMMENT '投机/套保',
  `cjj` varchar(20) DEFAULT NULL COMMENT '成交价',
  `ss` varchar(10) DEFAULT NULL COMMENT '手数',
  `cje` varchar(20) DEFAULT NULL COMMENT '成交额',
  `kp` varchar(2) DEFAULT NULL COMMENT '开/平',
  `sxf` varchar(20) DEFAULT NULL COMMENT '手续费',
  `pcyk` varchar(20) DEFAULT NULL COMMENT '平仓盈亏',
  `sjcjrq` varchar(25) DEFAULT NULL COMMENT '实际成交日期',
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2681119 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_jkzxbills_copy
-- ----------------------------
DROP TABLE IF EXISTS `rpa_jkzxbills_copy`;
CREATE TABLE `rpa_jkzxbills_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) DEFAULT NULL COMMENT '账号',
  `nbqqzjzh` varchar(25) DEFAULT NULL COMMENT '客户期货期权内部资金账户',
  `jymonth` varchar(11) DEFAULT NULL COMMENT '交易月份',
  `name` varchar(15) DEFAULT NULL COMMENT '客户名称',
  `cxtime` varchar(30) DEFAULT NULL COMMENT '查询时间',
  `qhname` varchar(50) DEFAULT NULL COMMENT '期货公司名称',
  `nbzqzjzh` varchar(25) DEFAULT NULL COMMENT '客户证券现货内部资金账户',
  `jyrq` varchar(30) NOT NULL COMMENT '交易日期',
  `hy` varchar(100) DEFAULT NULL COMMENT '合约',
  `cjxh` varchar(100) NOT NULL COMMENT '成交序号',
  `cjsj` varchar(25) DEFAULT NULL COMMENT '成交时间',
  `mm` varchar(2) DEFAULT NULL COMMENT '买/卖',
  `tjtb` varchar(4) DEFAULT NULL COMMENT '投机/套保',
  `cjj` varchar(20) DEFAULT NULL COMMENT '成交价',
  `ss` varchar(10) DEFAULT NULL COMMENT '手数',
  `cje` varchar(20) DEFAULT NULL COMMENT '成交额',
  `kp` varchar(2) DEFAULT NULL COMMENT '开/平',
  `sxf` varchar(20) DEFAULT NULL COMMENT '手续费',
  `pcyk` varchar(20) DEFAULT NULL COMMENT '平仓盈亏',
  `sjcjrq` varchar(25) DEFAULT NULL COMMENT '实际成交日期',
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_PersonID` (`jyrq`,`cjxh`)
) ENGINE=InnoDB AUTO_INCREMENT=86674 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_jysggs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_jysggs`;
CREATE TABLE `rpa_jysggs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exchangename` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` date DEFAULT NULL,
  `url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `filename` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filepath` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=642 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_kh_flows
-- ----------------------------
DROP TABLE IF EXISTS `rpa_kh_flows`;
CREATE TABLE `rpa_kh_flows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户id',
  `business_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '业务表id',
  `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务表id',
  `number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '流水号',
  `updated_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1 正常  -1 删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=657 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客户中间表';

-- ----------------------------
-- Table structure for rpa_khs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_khs`;
CREATE TABLE `rpa_khs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `sfz` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '身份证',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '电话',
  `address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '地址',
  `postcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮编',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `zjzh` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资金账号',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=655 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客户信息';

-- ----------------------------
-- Table structure for rpa_maintenances
-- ----------------------------
DROP TABLE IF EXISTS `rpa_maintenances`;
CREATE TABLE `rpa_maintenances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '软件名',
  `bewrite` varchar(255) DEFAULT NULL COMMENT '描述',
  `filepath` varchar(100) DEFAULT NULL COMMENT '软件位置',
  `failtimes` int(11) DEFAULT NULL COMMENT '失败尝试次数',
  `timeout` int(11) DEFAULT NULL COMMENT '超时',
  `isfp` int(2) NOT NULL DEFAULT '0' COMMENT '是否占用资源。1：是，0：否',
  `notice_type` tinyint(1) DEFAULT NULL COMMENT '收件人,'';''隔开',
  `noticeAccepter` varchar(50) DEFAULT NULL COMMENT '短信接收人,'',''隔开',
  `PaS` varchar(100) DEFAULT NULL COMMENT '主从服务器',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_naspaths
-- ----------------------------
DROP TABLE IF EXISTS `rpa_naspaths`;
CREATE TABLE `rpa_naspaths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zjzh` varchar(255) NOT NULL,
  `khqc` varchar(255) NOT NULL,
  `zjbh` varchar(255) NOT NULL,
  `khrq` varchar(15) NOT NULL,
  `mainpath` varchar(255) DEFAULT NULL,
  `CFApath` text COMMENT '期货失信截图',
  `SFpath` varchar(255) DEFAULT NULL COMMENT '证券失信截图',
  `PDFpath` varchar(255) DEFAULT NULL COMMENT '客户PDF',
  `Videopath` text COMMENT '开户视频',
  `HSpath` varchar(255) DEFAULT NULL COMMENT '恒生黑名单',
  `XYZGpath` text COMMENT '信用中国',
  `UF20path` text COMMENT 'UF2.0截图',
  `sfz_zm` varchar(255) DEFAULT NULL,
  `sfz_fm` varchar(255) DEFAULT NULL,
  `sign` varchar(255) DEFAULT NULL,
  `highRisk_pdf` varchar(255) DEFAULT NULL,
  `transaction50_pdf` varchar(255) DEFAULT NULL,
  `riskStatement_pdf` varchar(255) DEFAULT NULL,
  `limit_pdf` varchar(255) DEFAULT NULL,
  `jkzx_html` text,
  `jkzx_pdf` text,
  `jkzx_png` text,
  `updatetime` varchar(25) DEFAULT NULL,
  `isonline` varchar(5) DEFAULT NULL COMMENT '0线上，1线下',
  `addlg` varchar(5) DEFAULT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `tid` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66606 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_oabremindings
-- ----------------------------
DROP TABLE IF EXISTS `rpa_oabremindings`;
CREATE TABLE `rpa_oabremindings` (
  `khh` varchar(15) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `tid` int(1) NOT NULL COMMENT '类型',
  `day_one` varchar(25) DEFAULT NULL,
  `blance_one` double(18,4) DEFAULT NULL,
  `day_two` varchar(25) DEFAULT NULL,
  `blance_two` double(18,4) DEFAULT NULL,
  `day_three` varchar(25) DEFAULT NULL,
  `blance_three` double(18,4) DEFAULT NULL,
  `day_four` varchar(25) DEFAULT NULL,
  `blance_four` double(18,4) DEFAULT NULL,
  `day_five` varchar(25) DEFAULT NULL,
  `blance_five` double(18,4) DEFAULT NULL,
  `day_active` varchar(25) DEFAULT NULL,
  `blance_active` double(18,4) DEFAULT NULL,
  `updatetime` varchar(25) DEFAULT NULL,
  `state` int(2) DEFAULT '0' COMMENT '0未达标1已达标-1客户不存在2已处理',
  `created_at` varchar(25) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL COMMENT '注释',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blancenum` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1678 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_plugin_applys
-- ----------------------------
DROP TABLE IF EXISTS `rpa_plugin_applys`;
CREATE TABLE `rpa_plugin_applys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插件pk',
  `created_at` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '修改时间',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '申请人',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 申请  2 同意  3拒绝',
  `confirm` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '确认人',
  `confirm_time` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '确认时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='插件申请记录';

-- ----------------------------
-- Table structure for rpa_plugin_downloads
-- ----------------------------
DROP TABLE IF EXISTS `rpa_plugin_downloads`;
CREATE TABLE `rpa_plugin_downloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '下载人',
  `plugin_id` int(11) NOT NULL COMMENT '插件id',
  `version_id` int(11) NOT NULL COMMENT '版本id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='插件下载统计';

-- ----------------------------
-- Table structure for rpa_plugin_versions
-- ----------------------------
DROP TABLE IF EXISTS `rpa_plugin_versions`;
CREATE TABLE `rpa_plugin_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL COMMENT '插件id',
  `version` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '版本号',
  `show_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件显示名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态0关闭 1开启',
  `desc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述说明',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路劲',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统-插件版本';

-- ----------------------------
-- Table structure for rpa_plugins
-- ----------------------------
DROP TABLE IF EXISTS `rpa_plugins`;
CREATE TABLE `rpa_plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '插件名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态0关闭 1开启',
  `desc` text COLLATE utf8mb4_unicode_ci COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统插件管理';

-- ----------------------------
-- Table structure for rpa_pobo5_codes
-- ----------------------------
DROP TABLE IF EXISTS `rpa_pobo5_codes`;
CREATE TABLE `rpa_pobo5_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `innerCode` varchar(50) DEFAULT NULL COMMENT '博易大师内部代码',
  `futuShortName` varchar(50) DEFAULT NULL COMMENT '合约代码',
  `futuName` varchar(50) DEFAULT NULL COMMENT '合约名称',
  `futuKind` varchar(50) DEFAULT NULL COMMENT '合约品种',
  `created_at` varchar(50) DEFAULT NULL,
  `filePath` varchar(255) DEFAULT NULL COMMENT '文件路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3801 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_profession_change
-- ----------------------------
DROP TABLE IF EXISTS `rpa_profession_change`;
CREATE TABLE `rpa_profession_change` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profession_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '职业代码',
  `created_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1 登录  3 申请完成  4 成功  5失败 6有风险评估',
  `updated_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handle_status` tinyint(1) DEFAULT '0' COMMENT '处理状态 from py 0未处理 1已上报 2结束 3人工处理',
  `confirm` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '确认人',
  `confirm_time` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '确认时间',
  `shangbao_at` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '上报日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=657 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_profession_change_copy
-- ----------------------------
DROP TABLE IF EXISTS `rpa_profession_change_copy`;
CREATE TABLE `rpa_profession_change_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profession_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '职业代码',
  `created_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1 登录  3 申请完成  4 成功  5失败',
  `updated_at` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handle_status` tinyint(1) DEFAULT '0' COMMENT '处理状态 from py 0未处理 1已上报 2结束 3人工处理',
  `confirm` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '确认人',
  `confirm_time` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '确认时间',
  `shangbao_at` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '上报日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=358 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_releasecollections
-- ----------------------------
DROP TABLE IF EXISTS `rpa_releasecollections`;
CREATE TABLE `rpa_releasecollections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `week` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `jsondata` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updatetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `notice_type` tinyint(1) DEFAULT NULL COMMENT '收件人',
  `noticeAccepter` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `server` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_releasetasks
-- ----------------------------
DROP TABLE IF EXISTS `rpa_releasetasks`;
CREATE TABLE `rpa_releasetasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `week` varchar(20) DEFAULT NULL COMMENT '周几',
  `time` varchar(1000) NOT NULL COMMENT '时间',
  `jsondata` text COMMENT 'json格式的条件',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `state` int(2) DEFAULT NULL COMMENT '0:不发布，非0或空：发布，2：将改成0',
  `implement_type` tinyint(1) DEFAULT NULL COMMENT '是否自定义时间',
  `start_time` varchar(15) DEFAULT NULL,
  `end_time` varchar(15) DEFAULT NULL,
  `mins` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_reviewtables
-- ----------------------------
DROP TABLE IF EXISTS `rpa_reviewtables`;
CREATE TABLE `rpa_reviewtables` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `capital` varchar(12) NOT NULL COMMENT '资金账号',
  `customername` varchar(20) DEFAULT NULL COMMENT '客户姓名',
  `sex` varchar(5) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL COMMENT '电话',
  `videoPeopName` varchar(20) NOT NULL COMMENT '视频人名称',
  `checkPeopName` varchar(20) NOT NULL COMMENT '审核人名称',
  `reviewPeopName` varchar(20) NOT NULL COMMENT '回访人名称',
  `openingTime` varchar(20) DEFAULT NULL COMMENT '开户时间',
  `runtime` varchar(20) NOT NULL COMMENT '生成事件',
  `message` varchar(255) DEFAULT NULL COMMENT '回访备注',
  `reviewTime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0未回访 1已回访',
  `ischeck` int(1) DEFAULT '0' COMMENT '0否 1是',
  `reason` varchar(50) DEFAULT NULL,
  `bz` text,
  `khyj` text,
  PRIMARY KEY (`id`,`capital`)
) ENGINE=InnoDB AUTO_INCREMENT=28824 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rpa_shixincfas
-- ----------------------------
DROP TABLE IF EXISTS `rpa_shixincfas`;
CREATE TABLE `rpa_shixincfas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `idnum` varchar(25) DEFAULT NULL,
  `state` varchar(5) DEFAULT NULL COMMENT '无失信是0，有失信是1，错误是-1',
  `updatetime` varchar(25) DEFAULT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `operator` text,
  `count` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28645 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_shixinhss
-- ----------------------------
DROP TABLE IF EXISTS `rpa_shixinhss`;
CREATE TABLE `rpa_shixinhss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `idnum` varchar(25) DEFAULT NULL,
  `state` varchar(5) DEFAULT NULL COMMENT '无失信是0，有失信是1，错误是-1',
  `updatetime` varchar(25) DEFAULT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `operator` text,
  `count` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=326 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_shixinsfs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_shixinsfs`;
CREATE TABLE `rpa_shixinsfs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `idnum` varchar(25) DEFAULT NULL,
  `state` varchar(5) DEFAULT NULL COMMENT '无失信是0，有失信是1，错误是-1',
  `updatetime` varchar(25) DEFAULT NULL,
  `operator` text,
  `created_at` varchar(25) DEFAULT NULL,
  `count` int(11) DEFAULT '1' COMMENT '查询次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29255 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_simulation_account
-- ----------------------------
DROP TABLE IF EXISTS `rpa_simulation_account`;
CREATE TABLE `rpa_simulation_account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `sfz` varchar(40) NOT NULL COMMENT '身份证',
  `phone` varchar(30) NOT NULL COMMENT '电话',
  `address` varchar(100) NOT NULL COMMENT '地址',
  `postcode` varchar(16) NOT NULL COMMENT '邮编',
  `entrydate` int(15) DEFAULT NULL COMMENT '录入时间',
  `khdate` int(15) DEFAULT NULL COMMENT '开户时间',
  `zjzh` varchar(100) DEFAULT '' COMMENT '资金账户或者原因',
  `email` varchar(100) DEFAULT NULL,
  `isCtp` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否仅CTP穿透测试',
  `created_at` varchar(30) DEFAULT NULL,
  `updated_at` varchar(30) DEFAULT NULL,
  `ctp_time` varchar(30) DEFAULT NULL,
  `ctp_person` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1323 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_taskcollections
-- ----------------------------
DROP TABLE IF EXISTS `rpa_taskcollections`;
CREATE TABLE `rpa_taskcollections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL COMMENT '开始时间',
  `name` varchar(255) DEFAULT NULL,
  `bewrite` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `failtimes` int(11) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `finishtime` datetime DEFAULT NULL,
  `jsondata` text COMMENT 'json格式的条件',
  `emailreceiver` varchar(255) DEFAULT NULL COMMENT '收件人',
  `PhoneNum` varchar(255) DEFAULT NULL,
  `content` text COMMENT '邮件内容',
  `SMS` text COMMENT '短信内容',
  `updatetime` datetime DEFAULT NULL COMMENT '开始时间',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `limittime` datetime DEFAULT NULL COMMENT '超时时间',
  `server` varchar(10) DEFAULT NULL COMMENT '服务器',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注',
  `PaS` varchar(7) DEFAULT NULL COMMENT '主从',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=313360 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_timetasks
-- ----------------------------
DROP TABLE IF EXISTS `rpa_timetasks`;
CREATE TABLE `rpa_timetasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL COMMENT '开始时间',
  `name` varchar(255) NOT NULL COMMENT '程序名称（没有后缀）',
  `state` varchar(255) DEFAULT NULL COMMENT '状态',
  `jsondata` text COMMENT 'json格式的条件',
  `updatetime` datetime DEFAULT NULL,
  `limittime` datetime DEFAULT NULL COMMENT '服务器',
  `server` varchar(10) DEFAULT NULL COMMENT '服务器',
  `remarks` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49179 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_trade_loginrecord
-- ----------------------------
DROP TABLE IF EXISTS `rpa_trade_loginrecord`;
CREATE TABLE `rpa_trade_loginrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zjzh` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '资金账号',
  `tzjh_account` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '投资江湖账号',
  `ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '本地ip',
  `ip2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '互联网ip',
  `province` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省',
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '城市',
  `mac` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '登录MAC',
  `start_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '开始登录时间',
  `end_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结束登录时间',
  `count_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '总时长',
  `version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '登录版本',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for rpa_uploademails
-- ----------------------------
DROP TABLE IF EXISTS `rpa_uploademails`;
CREATE TABLE `rpa_uploademails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `content` text COMMENT '邮件内容',
  `state` varchar(255) DEFAULT NULL COMMENT '状态',
  `attachment` longblob COMMENT '附件',
  `attachmentname` varchar(255) DEFAULT NULL COMMENT '附件名称',
  `returnweb` text COMMENT '返回给web数据',
  `SMS` varchar(255) DEFAULT NULL COMMENT '短信内容',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=330300 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rpa_xyzgsxs
-- ----------------------------
DROP TABLE IF EXISTS `rpa_xyzgsxs`;
CREATE TABLE `rpa_xyzgsxs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provinces` varchar(30) DEFAULT NULL COMMENT '省份',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `idnum` varchar(30) DEFAULT NULL COMMENT '身份证号',
  `charge` varchar(255) DEFAULT NULL COMMENT '罪名',
  `reference` varchar(255) DEFAULT NULL COMMENT '案号',
  `judgmentmaking` varchar(255) DEFAULT NULL COMMENT '判决作出机构',
  `tyshxydm` varchar(255) DEFAULT NULL COMMENT '统一社会信用代码',
  `courtexecution` varchar(255) DEFAULT NULL COMMENT '执行法院',
  `representative` varchar(255) DEFAULT NULL COMMENT '法定代表人',
  `formtype` varchar(20) DEFAULT NULL COMMENT '失信类别',
  `periodsnum` varchar(10) DEFAULT NULL COMMENT '期数',
  `created_at` varchar(255) DEFAULT NULL COMMENT '更新时间',
  `fbdate` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23078 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_admin_groups
-- ----------------------------
DROP TABLE IF EXISTS `sys_admin_groups`;
CREATE TABLE `sys_admin_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_admins
-- ----------------------------
DROP TABLE IF EXISTS `sys_admins`;
CREATE TABLE `sys_admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` mediumint(4) NOT NULL COMMENT '部门归属',
  `leader_id` mediumint(4) DEFAULT NULL COMMENT '直接上级',
  `posts` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '岗位',
  `func_id` mediumint(4) NOT NULL COMMENT '职务',
  `work_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '工作号',
  `head_img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '上传头像',
  `name` varchar(191) CHARACTER SET utf8mb4 NOT NULL COMMENT '昵称',
  `realName` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '真实姓名',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别1男 2女',
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常 2离职 3冻结 4注销',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '地址',
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号码',
  `groupID` mediumint(4) NOT NULL COMMENT '分组id',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email',
  `roleLists` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '角色id',
  `type` tinyint(1) DEFAULT '0' COMMENT '状态 1启用 0禁用',
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ip',
  `desc` text COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `login_protected` tinyint(1) DEFAULT '0' COMMENT '是否登录保护',
  `last_session` text COLLATE utf8mb4_unicode_ci COMMENT '是否开启单点登录',
  `accept_mes_info` tinyint(1) DEFAULT '1' COMMENT '接受短信邮件信息通知',
  `accept_mes_type` tinyint(1) DEFAULT '3' COMMENT '接受信息通知类型1、短信 2、邮件 3、短信和邮件',
  `theme` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '皮肤',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `notification_count` mediumint(4) unsigned NOT NULL DEFAULT '0',
  `error_count` tinyint(2) DEFAULT '0' COMMENT '登录错误次数',
  `first_login` tinyint(1) DEFAULT '1' COMMENT '第一次登录 1 是 0否',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_bugs
-- ----------------------------
DROP TABLE IF EXISTS `sys_bugs`;
CREATE TABLE `sys_bugs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT '0',
  `msg` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_call_center_blacklists
-- ----------------------------
DROP TABLE IF EXISTS `sys_call_center_blacklists`;
CREATE TABLE `sys_call_center_blacklists` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='黑名单';

-- ----------------------------
-- Table structure for sys_call_center_customers
-- ----------------------------
DROP TABLE IF EXISTS `sys_call_center_customers`;
CREATE TABLE `sys_call_center_customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `zjzh` varchar(20) DEFAULT NULL COMMENT '资金账号',
  `card` varchar(20) DEFAULT NULL COMMENT '身份证',
  `source` varchar(50) DEFAULT NULL COMMENT '来源 ',
  `created_at` varchar(50) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  `khrq` varchar(20) DEFAULT NULL COMMENT '开户日期',
  `zjqy` varchar(20) DEFAULT NULL COMMENT '当日资金权益',
  `gtsj` varchar(20) DEFAULT NULL COMMENT '柜台手机',
  `client` varchar(20) DEFAULT NULL COMMENT '客户端',
  `jybm` varchar(255) DEFAULT NULL COMMENT '交易所 交易编码 ',
  `ip` varchar(30) DEFAULT NULL,
  `yq` tinyint(1) DEFAULT '0' COMMENT '银期关联  0未关联 1已关联 2已解绑',
  `fxys` varchar(10) DEFAULT NULL COMMENT '风险要素  ',
  `yyb` varchar(30) DEFAULT NULL COMMENT '营业部',
  `sxf` varchar(30) DEFAULT NULL,
  `bzj` varchar(30) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='客服中心-客户表';

-- ----------------------------
-- Table structure for sys_call_center_managers
-- ----------------------------
DROP TABLE IF EXISTS `sys_call_center_managers`;
CREATE TABLE `sys_call_center_managers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `sys_admin_id` int(10) unsigned NOT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `work_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '工号',
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标签 用逗号分隔',
  `desc` text COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客服中心-客服信息补充';

-- ----------------------------
-- Table structure for sys_call_center_message_templates
-- ----------------------------
DROP TABLE IF EXISTS `sys_call_center_message_templates`;
CREATE TABLE `sys_call_center_message_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COMMENT '内容',
  `answer` text COMMENT '答案',
  `group_id` tinyint(4) DEFAULT '1' COMMENT '分组id',
  `sort` smallint(6) DEFAULT '99' COMMENT '排序  小在前',
  `keyword` varchar(100) DEFAULT NULL COMMENT '关键词',
  `count` int(11) DEFAULT NULL COMMENT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='客服中心-信息模板';

-- ----------------------------
-- Table structure for sys_call_center_record_details
-- ----------------------------
DROP TABLE IF EXISTS `sys_call_center_record_details`;
CREATE TABLE `sys_call_center_record_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '聊天记录id',
  `manager_id` int(11) NOT NULL COMMENT '客服id',
  `customer_id` int(11) NOT NULL COMMENT '客户id',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '开始时间',
  `content` text NOT NULL COMMENT '内容',
  `sender` varchar(20) NOT NULL COMMENT '发送者',
  `type` enum('message','template') NOT NULL DEFAULT 'message' COMMENT '类型  1 信息  2 模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1273 DEFAULT CHARSET=utf8 COMMENT='客服中心-聊天详细记录';

-- ----------------------------
-- Table structure for sys_call_center_records
-- ----------------------------
DROP TABLE IF EXISTS `sys_call_center_records`;
CREATE TABLE `sys_call_center_records` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) NOT NULL DEFAULT '0' COMMENT '客服id',
  `customer_id` int(10) NOT NULL DEFAULT '0' COMMENT '客户id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客服中心-会话记录';

-- ----------------------------
-- Table structure for sys_call_center_settings
-- ----------------------------
DROP TABLE IF EXISTS `sys_call_center_settings`;
CREATE TABLE `sys_call_center_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '表单类型',
  `desc` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '描述信息',
  `group` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分组  1 基本设置',
  `sort` tinyint(4) NOT NULL DEFAULT '99' COMMENT '小在前',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客服中心-设置';

-- ----------------------------
-- Table structure for sys_configs
-- ----------------------------
DROP TABLE IF EXISTS `sys_configs`;
CREATE TABLE `sys_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `tip` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for sys_dept_post_relations
-- ----------------------------
DROP TABLE IF EXISTS `sys_dept_post_relations`;
CREATE TABLE `sys_dept_post_relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) NOT NULL COMMENT '部门id',
  `post_id` int(11) unsigned NOT NULL COMMENT '岗位id',
  `fullname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '全名',
  `duty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '岗位职责',
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '任职资格',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `dept_id` (`dept_id`),
  CONSTRAINT `dept_id` FOREIGN KEY (`dept_id`) REFERENCES `sys_depts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_id` FOREIGN KEY (`post_id`) REFERENCES `sys_dept_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='部门岗位关系表';

-- ----------------------------
-- Table structure for sys_dept_posts
-- ----------------------------
DROP TABLE IF EXISTS `sys_dept_posts`;
CREATE TABLE `sys_dept_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '职能名称',
  `rank` mediumint(9) DEFAULT NULL COMMENT '等级',
  `unique_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='岗位表';

-- ----------------------------
-- Table structure for sys_dept_relations
-- ----------------------------
DROP TABLE IF EXISTS `sys_dept_relations`;
CREATE TABLE `sys_dept_relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_relation_id` mediumint(4) NOT NULL COMMENT 'sys_dept_post_relations 表id',
  `admin_id` int(11) unsigned NOT NULL COMMENT '人id',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `admin_id` FOREIGN KEY (`admin_id`) REFERENCES `sys_admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='岗位和人关系';

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
  `is_business` tinyint(1) DEFAULT '0' COMMENT '是否是业务部门',
  `order` tinyint(3) DEFAULT '1' COMMENT '排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `post_ids` text COLLATE utf8mb4_unicode_ci COMMENT '岗位json',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='组织架构';

-- ----------------------------
-- Table structure for sys_dictionaries
-- ----------------------------
DROP TABLE IF EXISTS `sys_dictionaries`;
CREATE TABLE `sys_dictionaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='数据字典';

-- ----------------------------
-- Table structure for sys_document_contents
-- ----------------------------
DROP TABLE IF EXISTS `sys_document_contents`;
CREATE TABLE `sys_document_contents` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `did` int(11) DEFAULT NULL COMMENT '菜单id',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `read_num` int(11) DEFAULT NULL COMMENT '阅读次数',
  `uploads` text COMMENT '文件地址',
  `content` text COMMENT '内容',
  `creater_id` int(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for sys_document_menus
-- ----------------------------
DROP TABLE IF EXISTS `sys_document_menus`;
CREATE TABLE `sys_document_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '文档标题',
  `parent_id` int(6) DEFAULT NULL,
  `order` int(2) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for sys_errors
-- ----------------------------
DROP TABLE IF EXISTS `sys_errors`;
CREATE TABLE `sys_errors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `function` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `info` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分组';

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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='实例数据表';

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
) ENGINE=InnoDB AUTO_INCREMENT=388 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='流程处理明细表\r\n\r\nstatus  0 刚创建  1 办理中 2 已转交  4 已打回  9 已结束';

-- ----------------------------
-- Table structure for sys_flow_instances
-- ----------------------------
DROP TABLE IF EXISTS `sys_flow_instances`;
CREATE TABLE `sys_flow_instances` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `parent_id` int(6) DEFAULT '0' COMMENT '父节点,0是无父节点',
  `jsplumb` mediumtext COLLATE utf8mb4_unicode_ci COMMENT '流程图缓存数据',
  `work_num` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '工作流水',
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
  `status` tinyint(1) DEFAULT NULL COMMENT '状态  9 结束',
  `att_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '附件',
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '流程描述',
  `is_canceled` tinyint(1) DEFAULT NULL COMMENT '是否取消',
  `canceled_time` datetime DEFAULT NULL COMMENT '取消时间',
  `canceled_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '取消原因',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='流程实例';

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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='过程表';

-- ----------------------------
-- Table structure for sys_improvements
-- ----------------------------
DROP TABLE IF EXISTS `sys_improvements`;
CREATE TABLE `sys_improvements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT '0',
  `msg` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_logs`;
CREATE TABLE `sys_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `controller` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `simple_desc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38036 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_mails
-- ----------------------------
DROP TABLE IF EXISTS `sys_mails`;
CREATE TABLE `sys_mails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '//附件路劲',
  `tid` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9233 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_message_objects
-- ----------------------------
DROP TABLE IF EXISTS `sys_message_objects`;
CREATE TABLE `sys_message_objects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_message_types
-- ----------------------------
DROP TABLE IF EXISTS `sys_message_types`;
CREATE TABLE `sys_message_types` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for sys_messages
-- ----------------------------
DROP TABLE IF EXISTS `sys_messages`;
CREATE TABLE `sys_messages` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `content` text COMMENT '消息内容',
  `is_revoke` int(1) DEFAULT '0' COMMENT '是否撤销 0：否 1：是',
  `revoke_time` datetime DEFAULT NULL COMMENT '撤销时间',
  `is_delete` int(1) DEFAULT '0' COMMENT '是否删除 0：否 1：是',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  `type` int(2) DEFAULT NULL COMMENT '通知类型',
  `mode` int(1) DEFAULT NULL COMMENT '接受人类型 1单独推送 2角色推送 3全部推送',
  `user` varchar(255) DEFAULT NULL COMMENT '选择用户json 对应mode',
  `add_time` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33868 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for sys_model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_model_has_permissions`;
CREATE TABLE `sys_model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `sys_model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_model_has_roles`;
CREATE TABLE `sys_model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `sys_model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_permissions`;
CREATE TABLE `sys_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pid` int(6) DEFAULT NULL COMMENT '父id',
  `table` tinyint(2) NOT NULL DEFAULT '1' COMMENT '等级划分 1开始，2,3,4,5,6,7...',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态0关闭 1开启',
  `sort` int(6) DEFAULT NULL COMMENT '排序',
  `desc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_plugin_versions
-- ----------------------------
DROP TABLE IF EXISTS `sys_plugin_versions`;
CREATE TABLE `sys_plugin_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL COMMENT '插件id',
  `version` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '版本号',
  `show_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件显示名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态0关闭 1开启',
  `desc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述说明',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路劲',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统-插件版本';

-- ----------------------------
-- Table structure for sys_plugins
-- ----------------------------
DROP TABLE IF EXISTS `sys_plugins`;
CREATE TABLE `sys_plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '插件名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态0关闭 1开启',
  `desc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统插件管理';

-- ----------------------------
-- Table structure for sys_role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_has_permissions`;
CREATE TABLE `sys_role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `sys_role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE `sys_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_sms_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_sms_logs`;
CREATE TABLE `sys_sms_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '//类型',
  `api` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'api接口名称',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '发送内容',
  `return` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '返回值',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14171 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_sms_tpls
-- ----------------------------
DROP TABLE IF EXISTS `sys_sms_tpls`;
CREATE TABLE `sys_sms_tpls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate` varchar(50) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL COMMENT '//类型',
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `created_at` varchar(50) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for sys_user_mails
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_mails`;
CREATE TABLE `sys_user_mails` (
  `mid` int(10) NOT NULL COMMENT '//邮件id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `read_at` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '//读取时间，空代表未读',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1、收件箱 2、发件箱 3、草稿箱 4、回收箱',
  `created_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_version_updates
-- ----------------------------
DROP TABLE IF EXISTS `sys_version_updates`;
CREATE TABLE `sys_version_updates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT NULL COMMENT '类型 1正常更新 2版本升级  3紧急维护',
  `desc` text COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `created_at` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 正常',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='版本更新';

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notification_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
