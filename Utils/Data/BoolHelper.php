<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/8/13
 * Time: 8:16
 */

namespace Hiland\Utils\Data;


class BoolHelper
{
    /**
     * 判断变量是否为真实的布尔型true
     * @param $value
     * @return bool
     */
    public static function isRealTrue($value)
    {
        if (is_bool($value) && $value == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断变量是否为真实的布尔型false
     * @param $value
     * @return bool
     */
    public static function isRealFalse($value)
    {
        if (is_bool($value) && $value == false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取布尔值的文本显示
     * @param $value
     * @return string
     */
    public static function getText($value){
        if($value){
            return "true";
        }else{
            return "false";
        }
    }
}