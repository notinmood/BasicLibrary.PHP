<?php
/**
 * @file   : ConfigHelper.php
 * @time   : 14:05
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

/**
 * 配置文件辅助类(向外部暴露的接口)
 * @TODO 需要修改成：通过loadFile加载的配置文件是长效性(即后面可以多次使用get获取配置项);
 * @TODO 通过get的第三个参数加载的配置的文件是临时性(只在本次get时有效)。
 */
class ConfigHelper
{
    /**
     * 加载配置文件
     * @param string $fileName 配置文件(携带有相对于网站根目录的相对路径,缺省情况下加载项目根目录下的 config.php)
     *                         (目前支持的文件类型有:.php(内部返回Array)、.ini和.json等多种格式)
     * @return void
     */
    public static function loadFile($fileName = "")
    {
        ConfigMate::Instance()->loadFile($fileName);
    }

    /**
     * @param string $key
     * @param null   $default
     * @param null   $fileName 配置文件(携带有相对于网站根目录的相对路径)
     *                         (目前支持的文件类型有:.php(内部返回Array)、.ini和.json等多种格式)
     * @return array|bool|string|null
     */
    public static function get($key, $default = null, $fileName = null)
    {
        $configMate = ConfigMate::Instance();

        /**
         * 此配置文件是否已经加载过，在 $configMate 内判断
         */
        $configMate->loadFile($fileName);

        $result = $configMate->get($key, $default);
        return dotEnvHelper::get($key, $result);
    }
}