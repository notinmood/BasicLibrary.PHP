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
    public static function convertPercentToFloat($data)
    {
        return (float)$data / 100;
    }

    /**
     * 小数转百分比
     * @param     $data
     * @param int $precision 精度位数
     * @return string 带%的百分比字符串
     */
    public static function convertFloatToPercent($data, $precision = 2)
    {
        $format = "%01." . $precision . "f";
        return sprintf($format, $data * 100) . '%';
    }

    /**获取一个数列的简单的移动平均值
     * @param        $sourceArray
     * @param        $period          int 滑动时间周期(比如每5天作为一个周期计算一次平均值)
     * @param string $targetFieldName 如果是一维数组可以忽略本参数；如果是二维数组，请指定需要进行计算的字段名称。
     * @return array
     */
    public static function sma($sourceArray, $period, $targetFieldName = '')
    {
        $result = null;
        $level = ArrayHelper::getLevel($sourceArray);
        $pIndex = $period - 1;
        $data = array_values($sourceArray);
        $sum = 0;

        foreach ($data as $k => $v) {
            if ($level == 1) {
                $currentValue = $v;
                $needRemoveValue = $data[$k - $pIndex] ? $data[$k - $pIndex] : 0;
            } else {
                $currentValue = $v[$targetFieldName];
                $needRemoveValue = $data[$k - $pIndex][$targetFieldName] ? $data[$k - $pIndex][$targetFieldName] : 0;
            }

            $sum += $currentValue;
            if ($k < $pIndex) {
                $item = 0;
            } else {
                $item = sprintf("%.2f", ($sum / $period));
                $sum -= $needRemoveValue;
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * 数学中各种进制之间的相互转换
     * @param string $numberString 待转换的(字符串格式的)目标数字
     * @param int    $fromBase     从**进制转换
     * @param int    $toBase       转换到**进制
     * @return string
     */
    public static function convertBase($numberString, $fromBase, $toBase)
    {
        return base_convert($numberString, $fromBase, $toBase);
    }

}