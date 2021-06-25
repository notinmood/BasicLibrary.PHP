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

    /**获取一个数列的简单的移动平均值
     * @param $sourceArray
     * @param $period
     * @param string $targetFieldName 如果是一维数组可以忽略本参数；如果是二维数组，请指定需要进行计算的字段名称。
     * @return int
     */
    public static function sma($sourceArray, $period, $targetFieldName = '')
    {
        $result = null;
        $level = ArrayHelper::getLevel($sourceArray);
        $pIndex = $period - 1;
        $data = array_values($sourceArray);
        $sum = 0;
        if ($level == 1) {
            //计算移动平均值
            foreach ($data as $k => $v) {
                $sum += $v;
                if ($k < $pIndex) {
                    $item = 0;
                } else {
                    $item = sprintf("%.2f", ($sum / $period));
                    $sum -= $data[$k - $pIndex] ? $data[$k - $pIndex] : 0;
                }

                $result[] = $item;
            }
        } else {
            //计算移动平均值
            foreach ($data as $k => $v) {
                $sum += $v[$targetFieldName];
                if ($k < $pIndex) {
                    $item = 0;
                } else {
                    $item = sprintf("%.2f", ($sum / $period));
                    $sum -= $data[$k - $pIndex][$targetFieldName] ? $data[$k - $pIndex][$targetFieldName] : 0;
                }

                $result[] = $item;
            }
        }

        return $result;
    }
}