<?php
/**
 * @file   : NumberHelper.php
 * @time   : 9:58
 * @date   : 2021/9/22
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Utils\Data;

class NumberHelper
{
    /**
     * 使用千分位的方式格式化数字为字符串
     * @param int|float $number 带格式化的数字
     * @param int       $decimalLength 小数位数（缺省为0)
     * @param string    $decimalPoint 小数点符号(缺省为".")
     * @param string    $thousandsSeperator 千分位分隔符(缺省为逗号",")
     * @return string
     */
    public static function format($number, $decimalLength = 0, $decimalPoint = ".", $thousandsSeperator = ",")
    {
        return number_format($number, $decimalLength, $decimalPoint, $thousandsSeperator);
    }
}