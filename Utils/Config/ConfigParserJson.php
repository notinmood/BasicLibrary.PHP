<?php
/**
 * @file   : ConfigParserJson.php
 * @time   : 8:45
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

use Hiland\Utils\Data\JsonHelper;
use Hiland\Utils\IO\FileHelper;

/**
 * json格式的内容解析器
 */
class ConfigParserJson extends ConfigParser
{
    public function loadFileToArray($fileFullName)
    {
        $content= FileHelper::getEncodingContent($fileFullName);
        return JsonHelper::convertToArray($content);
    }
}