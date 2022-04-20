<?php
/**
 * @file   : ConfigParser.php
 * @time   : 8:39
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

/**
 * (抽象的)配置文件解析器
 */
abstract class ConfigParser
{
    public function toString(): string
    {
        return get_called_class();
    }

    /**
     * 从存储系统载入配置文件,并形成array数组返回
     * @param $fileFullName
     */
    public function loadFileToArray($fileFullName)
    {
        //
    }
}
