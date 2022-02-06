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
 * (特别注意：如果在第一次使用 两个参数的get()方法前，使用了 三个参数的get或者loadFile,那么系统就不会再加载缺省的config.***文件了)
 */
class ConfigHelper
{
    /**
     * 加载配置文件
     * @param string $fileName 配置文件(携带有相对于网站根目录的相对路径,缺省情况下加载项目根目录下的 config.***)
     *                         (目前支持的文件类型有:.php(内部返回Array)、.ini和.json等多种格式)
     * @return void
     */
    public static function loadFile(string $fileName = "")
    {
        ConfigMate::Instance()->loadFile($fileName);
    }

    /**
     * 获取配置信息
     * @param string $key 配置节点的名称
     * @param null   $default 配置节点的缺省值
     * @param null   $fileName 配置文件(携带有相对于网站根目录的相对路径)
     *                         (目前支持的文件类型有:.php(内部返回Array)、.ini和.json等多种格式)
     * @return array|bool|string|null
     */
    public static function get(string $key, $default = null, $fileName = null)
    {
        $configMate = ConfigMate::Instance();

        if ($fileName) {
            $configMate->loadFile($fileName);
        }

        $result = $configMate->get($key, $default);
        return dotEnvHelper::get($key, $result);
    }

    /**
     * 从 .env 内获取配置信息
     * @param string $key          配置节点的名称
     * @param null   $defaultValue 配置节点的缺省值
     * @return array|bool|string|null
     */
    public static function getEnv(string $key, $defaultValue = null)
    {
        return dotEnvHelper::get($key, $defaultValue);
    }
}