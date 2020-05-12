<?php


namespace Hiland\Utils\Data;

use think\Config;

/**
 * 对thinkphp的包装，主要用于thinkphp各个版本的兼容
 * Class ThinkHelper
 * @package Hiland\Utils\Data
 */
class ThinkHelper
{
    /**
     * 获取当前使用thinkphp的版本
     * @return string
     */
    public static function getVersion()
    {
        $verion = "0";

        //---------------------------------------------
        // thinkphp3和5中，版本号保存在THINK_VERSION里面；
        // thinkphp6中，版本号保存在think\App::VERSION里面；
        //---------------------------------------------
        if (defined('think\App::VERSION')) {
            $verion = \think\App::VERSION;
        } else {
            $verion = THINK_VERSION;
        }

        return $verion;
    }

    /**
     * 获取当前使用thinkphp的主版本
     * @return int
     */
    public static function getMainVersion()
    {
        return (int)self::getVersion();
    }

    /**对配置节点的读取进行包装（主要是兼容thinkphp的各个版本）
     * @param string|null $name
     */
    public static function config($name = null)
    {
        $value = null;
        if (self::getMainVersion() < 6) {
            $value = Config::get($name);
        } else {
            $value = \config($name);
        }

        return $value;
    }
}