<?php
/**
 * @file   : Container.php
 * @time   : 9:10
 * @date   : 2021/12/31
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\DataModel;

/**
 * 容器类，用于存放数据
 */
class Container
{
    private static array $items = [];

    /**
     * 获取指定名称的项目
     * @param string $name
     * @param null   $defaultValue
     * @return mixed
     */
    public static function get(string $name, $defaultValue = Null): mixed
    {
        foreach (self::$items as $k => $v) {
            if ($k === $name) {
                return $v;
            }
        }

        return $defaultValue;
    }

    /**
     * 设置指定名称的项目
     * @param $name
     * @param $value
     * @return void
     */
    public static function set($name, $value): void
    {
        self::$items[$name] = $value;
    }
}
