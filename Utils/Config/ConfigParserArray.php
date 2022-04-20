<?php
/**
 * @file   : ConfigClient.php
 * @time   : 19:55
 * @date   : 2021/9/5
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

/**
 * 内部返回数组类型的并用.php文件表述的文件解析器(缺省的解析器)
 */
class ConfigParserArray extends ConfigParser
{
    public function loadFileToArray($fileFullName)
    {
        return require $fileFullName;
    }
}
