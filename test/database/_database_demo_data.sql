/*
 Navicat MySQL Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50723
 Source Host           : localhost:3306
 Source Schema         : mydemo

 Target Server Type    : MySQL
 Target Server Version : 50723
 File Encoding         : 65001

 Date: 28/12/2021 11:11:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tmp_user
-- ----------------------------
DROP TABLE IF EXISTS `tmp_user`;
CREATE TABLE `tmp_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `birthday` datetime NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `class` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `score` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tmp_user
-- ----------------------------
INSERT INTO `tmp_user` VALUES (1, 'zhangsan', '2021-12-24 09:07:05', '266000@sina.com', '一', 66);
INSERT INTO `tmp_user` VALUES (2, 'lisi', '2021-12-15 09:07:26', '277521@qq.com', '三', 93);
INSERT INTO `tmp_user` VALUES (3, 'zhangsan', '2021-12-24 09:07:05', 'aa@qq.com', '二', 88);
INSERT INTO `tmp_user` VALUES (4, 'hah', '2021-12-22 10:07:47', 'wps@foxmail.com', '一', 97);

SET FOREIGN_KEY_CHECKS = 1;


/*
 Navicat MySQL Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50723
 Source Host           : localhost:3306
 Source Schema         : mydemo

 Target Server Type    : MySQL
 Target Server Version : 50723
 File Encoding         : 65001

 Date: 02/01/2022 19:18:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tmp_student
-- ----------------------------
DROP TABLE IF EXISTS `tmp_student`;
CREATE TABLE `tmp_student`  (
                                `sid` int(11) NOT NULL AUTO_INCREMENT,
                                `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                                `birthday` datetime NULL DEFAULT NULL,
                                `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                                `class` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                                `score` int(11) NULL DEFAULT NULL,
                                PRIMARY KEY (`sid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tmp_student
-- ----------------------------
INSERT INTO `tmp_student` VALUES (1, 'zhangsan', '2021-12-24 09:07:05', '266000@sina.com', '一', 66);
INSERT INTO `tmp_student` VALUES (2, 'lisi', '2021-12-15 09:07:26', '277521@qq.com', '三', 93);
INSERT INTO `tmp_student` VALUES (3, 'zhangsan', '2021-12-24 09:07:05', 'aa@qq.com', '三', 88);
INSERT INTO `tmp_student` VALUES (4, 'hah', '2021-12-22 10:07:47', 'wps@foxmail.com', '一', 97);
INSERT INTO `tmp_student` VALUES (5, 'qingdao', '2021-12-22 10:07:47', 'qd@qq.com', '二', 87);
INSERT INTO `tmp_student` VALUES (6, 'beijing', '2021-12-22 10:07:47', 'bj@qq.com', '三', 58);
INSERT INTO `tmp_student` VALUES (13, 'shanghai', '2021-12-22 10:07:47', 'sh@qq.com', '二', 68);
INSERT INTO `tmp_student` VALUES (14, 'guangzhou', '2021-12-22 10:07:47', 'gz@qq.com', '三', 100);

SET FOREIGN_KEY_CHECKS = 1;
