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

class ConfigHelper
{
    public static function loadFile($fileName = "config.php")
    {
        ConfigMate::Instance()->loadFile($fileName);
    }

    public static function get($key, $default = null, $fileName = null)
    {
        $configMate = null;
        if ($fileName) {
            $configMate = ConfigMate::Instance()->loadFile($fileName);
        }

        if (!$configMate) {
            $configMate = ConfigMate::Instance();
        }

        return $configMate->get($key, $default);
    }
}