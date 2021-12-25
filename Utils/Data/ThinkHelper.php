<?php


namespace Hiland\Utils\Data;

use think\App;
use think\facade\Config;

/**
 * 对thinkphp的包装，主要用于thinkphp各个版本的兼容
 * Class ThinkHelper
 * @package Hiland\Utils\Data
 */
class ThinkHelper
{
    /**
     * 判断当前是否运行在ThinkPHP环境下
     * (目前是通过判断是否存在类型think\App或者think\Controller来判定的)
     * @return bool
     */
    public static function isThinkPHP()
    {
        if (class_exists("think\App")) {
            return true;
        }

        if (class_exists("think\Controller")) {
            return true;
        }

        return false;
    }

    /**获取thinkphp的主版本号
     * @return int
     */
    public static function getPrimaryVersion()
    {
        return self::getSomeSubVersion(0);
    }

    private static function getSomeSubVersion($pos)
    {
        $result = 0;

        $version = self::getVersion();
        $firstNode = StringHelper::getStringBeforeSeperator($version, " ");
        $secondNode = StringHelper::getStringAfterSeperator($version, " ");
        $arr = explode(".", $firstNode);
        $arr[] = $secondNode;

        if (ArrayHelper::containsKey($arr, $pos)) {
            $result = $arr[$pos];
        }

        return $result;
    }

    /**
     * 获取当前使用thinkphp的版本
     * @return string
     */
    public static function getVersion()
    {
        //---------------------------------------------
        // thinkphp3和5.0中，版本号保存在THINK_VERSION里面；
        // thinkphp5.1和6中，版本号保存在think\App::VERSION里面；
        //---------------------------------------------
        if (defined('think\App::VERSION')) {
            $version = \think\App::VERSION;
        } else {
            $version = THINK_VERSION;
        }

        return $version;
    }

    /**获取thinkphp的次版本号
     * @return int
     */
    public static function getSecondaryVersion()
    {
        return self::getSomeSubVersion(1);
    }

    /**获取thinkphp的修订版本号
     * @return int
     */
    public static function getRevisionVersion()
    {
        return self::getSomeSubVersion(2);
    }

    public static function getVersionAddon()
    {
        return self::getSomeSubVersion(3);
    }
}