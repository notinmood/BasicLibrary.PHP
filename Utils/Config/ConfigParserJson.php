<?php
/**
 * @file   : ConfigParserJson.php
 * @time   : 8:45
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

use Hiland\Utils\Data\JsonHelper;
use Hiland\Utils\IO\FileHelper;

/**
 * json 格式的内容解析器
 */
class ConfigParserJson extends ConfigParser
{
    public function loadFileToArray($fileFullName)
    {
        $content = FileHelper::getEncodingContent($fileFullName);
        return JsonHelper::convertToArray($content);
    }
}
