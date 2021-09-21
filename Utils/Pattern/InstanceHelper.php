<?php
/**
 * @file   : InstanceHelper.php
 * @time   : 20:04
 * @date   : 2021/9/21
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Utils\Pattern;

use Hiland\Utils\Data\StringHelper;

class InstanceHelper
{
    private static $_instances = [];

    public static function get($classFullName, ...$constructArgs)
    {
        $queryKey = $classFullName;
        if ($constructArgs) {
            $queryKey .= "||" . StringHelper::implode($constructArgs, "|");
        }

        if (array_key_exists($queryKey, self:: $_instances)) {
            return self::$_instances[$queryKey];
        } else {
            if ($constructArgs) {
                $instance = new $classFullName(...$constructArgs);
            } else {
                $instance = new $classFullName();
            }

            self:: $_instances[$queryKey] = $instance;
            return $instance;
        }
    }
}