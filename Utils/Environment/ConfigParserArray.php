<?php
/**
 * @file   : ConfigHelper.php
 * @time   : 19:55
 * @date   : 2021/9/5
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

class ConfigParserArray extends ConfigParser
{
    public function loadFileToArray($fileFullName)
    {
        return require $fileFullName;
    }
}