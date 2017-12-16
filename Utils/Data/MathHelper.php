<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/26
 * Time: 9:17
 */

namespace Hiland\Utils\Data;


class MathHelper
{
    /**
     * 百分比转小数
     * @param $data string 百分比字符串（可以带%也可以不带）
     * @return float
     */
    public static function percent2Float($data)
    {
        return (float)$data / 100;
    }

    /**
     * 小数转百分比
     * @param $data
     * @param int $precisionNumber 精度位数
     * @return string 带%的百分比字符串
     */
    public static function float2Percent($data, $precisionNumber = 2)
    {
        $format = "%01." . $precisionNumber . "f";
        return sprintf($format, $data * 100) . '%';
    }
}