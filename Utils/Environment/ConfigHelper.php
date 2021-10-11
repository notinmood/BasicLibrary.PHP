<?php
/**
 * @file   : ConfigHelper.php
 * @time   : 14:05
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

/**
 * 配置文件辅助类(外部使用此类)
 */
class ConfigHelper
{
    public static function loadFile($fileName = "config.php")
    {
        ConfigMate::Instance()->loadFile($fileName);
    }

    /**
     * @param string $key
     * @param null   $default
     * @param null   $fileName 配置文件(携带有相对于网站根目录的相对路径)
     *                         (目前支持的文件类型有:.php(内部返回Array)、.ini和.json3种格式)
     * @return mixed|null
     */
    public static function get($key, $default = null, $fileName = null)
    {
        $configMate = ConfigMate::Instance();

        if ($fileName) {
            $configMate->loadFile($fileName);
        }

        return $configMate->get($key, $default);
    }
}