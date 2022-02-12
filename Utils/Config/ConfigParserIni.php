<?php
/**
 * @file   : ConfigParserIni.php
 * @time   : 17:03
 * @date   : 2021/8/11
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\ObjectTypes;
use Hiland\Utils\Data\StringHelper;

/**
 * ini配置文件解析器
 * 参考 https://www.php.net/manual/zh/function.parse-ini-file.php
 */
class ConfigParserIni extends ConfigParser
{
    public function loadFileToArray($fileFullName): array
    {
        $ini    = parse_ini_file($fileFullName, true);
        $config = array();
        foreach ($ini as $namespace => $properties) {
            if (StringHelper::isContains($namespace, ":")) {
                list($name, $extends) = explode(':', $namespace);
            } else {
                $name    = $namespace;
                $extends = "";
            }

            $name    = trim($name);
            $extends = trim($extends);
            // create namespace if necessary
            if (!isset($config[$name])) {
                $config[$name] = array();
            }

            // inherit base namespace
            if (isset($ini[$extends])) {
                foreach ($ini[$extends] as $prop => $val) {
                    $config[$name][$prop] = $val;
                }
            }

            // overwrite / set current namespace values
            if (ObjectHelper::getTypeName($properties) == ObjectTypes::ARRAYS) {
                foreach ($properties as $prop => $val) {
                    $config[$name][$prop] = $val;
                }
            } else {
                $config[$name] = $properties;
            }
        }
        return $config;
    }
}