<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/11
 * Time: 10:22
 */

namespace Hiland\Biz\ThinkAddon;


use Hiland\Utils\Data\ThinkHelper;

class TPCompatibleHelper
{
    /**读取配置节点的信息
     * @param $settingName string 配置节点的名称
     */
    public static function config($settingName)
    {
        $result = '';
        if (ThinkHelper::getPrimaryVersion() > 3) {
            $nameNodes = explode(".", $settingName);

            if ($nameNodes[0]) {
                $result = config($nameNodes[0]);
            }

            for ($i = 1; $i < count($nameNodes); $i++) {
                $result = $result[$nameNodes[$i]];
            }
        } else {
            $result = C($settingName);
        }

        return $result;
    }

    /**
     * 读取/设置缓存
     * @param $name
     * @param $value
     * @param null $options
     * @param null $tags
     * @return mixed
     */
    public static function cache($name, $value = '', $options = null, $tags = null)
    {
        if (ThinkHelper::getPrimaryVersion() > 3) {
            $result = cache($name, $value, $options, $tags);
        } else {
            $result = S($name, $value, $options, $tags);
        }

        return $result;
    }
}