<?php
/**
 * @file   : ConfigParserIni.php
 * @time   : 17:03
 * @date   : 2021/8/11
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

/**
 * ini配置文件解析器
 * 参考 https://www.php.net/manual/zh/function.parse-ini-file.php
 */
class ConfigParserIni extends ConfigParser
{
    public function get($key)
    {

    }

    public function loadFileToArray($fileFullName)
    {
        $p_ini = parse_ini_file($fileFullName, true);
        $config = array();
        foreach ($p_ini as $namespace => $properties) {
            list($name, $extends) = explode(':', $namespace);
            $name = trim($name);
            $extends = trim($extends);
            // create namespace if necessary
            if (!isset($config[$name])) $config[$name] = array();
            // inherit base namespace
            if (isset($p_ini[$extends])) {
                foreach ($p_ini[$extends] as $prop => $val)
                    $config[$name][$prop] = $val;
            }
            // overwrite / set current namespace values
            foreach ($properties as $prop => $val)
                $config[$name][$prop] = $val;
        }
        return $config;
    }
}