<?php
/**
 * @file   : ConfigParserJson.php
 * @time   : 8:45
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

use Hiland\Utils\Data\JsonHelper;
use Hiland\Utils\IO\FileHelper;

class ConfigParserJson extends ConfigParser
{
    public function loadFileToArray($fileFullName)
    {
        // $p_ini = file_get_contents($fileFullName);
        $content= FileHelper::getEncodingContent($fileFullName);
        return JsonHelper::toArray($content);
    }
}