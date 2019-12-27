/*
Navicat MySQL Data Transfer

Source Server         : 172.16.253.170_3306
Source Server Version : 50724
Source Host           : 172.16.253.170:3306
Source Database       : rpa

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2019-12-27 17:36:07
*/

SET FOREIGN_KEY_CHECKS=0;

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
INSERT INTO `sys_menus` VALUES ('82', '流程管理', 'sys_flow', '84', '5', 'fa-circle-o', '/admin/flow', '1', '2019-11-19 15:49:46', '2019-12-04 10:33:51');
INSERT INTO `sys_menus` VALUES ('83', '部门管理', 'sys_dept', '20', '5', 'fa-circle', '/admin/sys_dept', '1', '2019-12-03 10:13:46', '2019-12-03 10:13:46');
INSERT INTO `sys_menus` VALUES ('84', '流程中心', 'sys_flow_center', '0', '5', 'fa-bars', '/admin/sys_flow_center', '1', '2019-12-04 10:33:33', '2019-12-04 10:35:08');
INSERT INTO `sys_menus` VALUES ('85', '我的流程', 'sys_flow_mine', '84', '5', 'fa-circle-o', '/admin/sys_flow_mine', '1', '2019-12-04 10:45:05', '2019-12-04 10:45:05');
