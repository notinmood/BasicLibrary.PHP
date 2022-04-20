<?php
/**
 * @file   : Container.php
 * @time   : 9:10
 * @date   : 2021/12/31
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

/**
 *
 */
class Container
{
    private static array $mates = [];

    /**
     * @param string $name
     * @param null   $defaultValue
     * @return mixed
     */
    public static function get(string $name, $defaultValue = Null)
    {
        foreach (self::$mates as $k => $v) {
            if ($k === $name) {
                return $v;
            }
        }

        return $defaultValue;
    }

    public static function set($name, $value)
    {
        self::$mates[$name] = $value;
    }
}
