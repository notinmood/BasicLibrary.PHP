<?php

namespace Hiland\Utils\DataModel;

/**
 * Class MateContainer Mate的容器，可以在一个请求之内重复利用已经创建的Mate，节省内存和效率
 * @package Hiland\Utils\DataModel
 */
class MateContainer
{
    private static $mates = null;

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        if(self::$mates){
            foreach (self::$mates as $k => $v) {
                if ($k === $name) {
                    return $v;
                }
            }
        }

        $mate = new ModelMate($name);
        self::$mates[$name] = $mate;
        return $mate;
    }
}