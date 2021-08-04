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
    public static function getText($value)
    {
        if ($value) {
            return "true";
        } else {
            return "false";
        }
    }

    /**将字符串等数据类型转换成bool
     * 因为一般的转换方式
     *  $string = 'false';
     * var_dump(settype($string, 'boolean'));
     * 得到的结果都是true。因此使用本方法进行操作。
     * @param mixed $value 待转换的数据
     * @param bool $return_null 转换失败是否返回null（默认false不转换null）
     * @return bool|mixed|null
     */
    public static function isTrue($value, $return_null = false)
    {
        $boolval = (is_string($value) ? filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool)$value);
        return ($boolval === null && !$return_null ? false : $boolval);
    }
}