<?php
namespace Hiland\Biz\Loger\DBLoger;

use Hiland\Utils\Data\GuidHelper;

/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/6/30
 * Time: 7:29
 */
/*
 *在数据库中保存的日志信息
 *
 *
 * 说明：需要在数据库内创建表 【前缀】_system_infolog
 *
 * SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `【前缀】_system_infolog`
-- ----------------------------
DROP TABLE IF EXISTS `【前缀】_system_infolog`;
CREATE TABLE `【前缀】_system_infolog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(36) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `category` varchar(20) NOT NULL,
  `other` varchar(200) NOT NULL,
  `misc` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
 */

class Loger
{
    /**
     * 进行日志记录
     *
     * @param string $title
     *            日志的标题
     * @param string $content
     *            日志的内容
     * @param string $categoryname
     *            日志的分类名称
     * @param string $other
     *            日志附加信息
     * @param int $misc1
     *            日志附加信息
     * @param string $status
     *            日志状态信息
     * @return boolean 日志记录的成功与失败
     */
    public function log($title, $content = '', $status = '', $categoryname = 'develop', $other = '', $misc = 0)
    {
        $result = true;

        if (C("WEIXIN_LOG_MODE") == null || C("WEIXIN_LOG_MODE") >= 0) {
            $model = D("systemInfolog");

            $data['guid'] = GuidHelper::newGuid();
            $data['title'] = (string)$title;
            $data['content'] = (string)$content;
            $data['category'] = $categoryname;
            $data['other'] = $other;
            $data['misc'] = $misc;
            $data['status'] = $status;
            $data['createtime'] = date('Y-m-d H:i:s',time());

            if ($model->add($data)) {
                $result = true;
            } else {
                $result = false;
            }
        }

        return $result;
    }
}