<?php
/**
 * @file   : NumberHelper.php
 * @time   : 9:58
 * @date   : 2021/9/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Data;

class NumberHelper
{
    /**
     * 使用千分位的方式格式化数字为字符串
     * @param float|int $number             带格式化的数字
     * @param int       $decimalLength      小数位数（缺省为0)
     * @param string    $decimalPoint       小数点符号(缺省为".")
     * @param string    $thousandsSeparator 千分位分隔符(缺省为逗号",")
     * @return string
     */
    public static function format(float|int $number, int $decimalLength = 0, string $decimalPoint = ".", string $thousandsSeparator = ","): string
    {
        return number_format($number, $decimalLength, $decimalPoint, $thousandsSeparator);
    }

    /**
     * 数学中各种进制之间的相互转换,返回字符串表示的数据（MathHelper::convertBase的别名）
     * @param string $numberString 待转换的(字符串格式的)目标数字
     * @param int    $fromBase     从**进制转换
     * @param int    $toBase       转换到**进制
     * @return string
     */
    public static function convert(string $numberString, int $fromBase, int $toBase): string
    {
        return MathHelper::convertBase($numberString, $fromBase, $toBase);
    }

    /**
     * 计算移动平均值
     * @param int[]|float[] $arrayData 待计算的数组
     * @param int $windowSize 窗口大小
     * @return array 移动平均值数组
     */
    public static function getMovingAverage(array $arrayData, int $windowSize): array {
        $arrLen = count($arrayData);
        if ($windowSize <= 0 || $arrLen < $windowSize) {
            return [];
        }

        // 构建前缀和数组
        $prefixSum = [];
        for ($i = 0; $i < $arrLen; $i++) {
            if ($i === 0) {
                $prefixSum[] = $arrayData[$i];
                continue;
            }

            $prefixSum[] = $prefixSum[$i - 1] + $arrayData[$i];
        }

        $result = [];
        // 利用前缀和快速计算窗口和
        for ($i = 0; $i < $arrLen; $i++) {
            if ($i < $windowSize - 1) {
                $result[] = NAN;
                continue;
            }

            $startSum = ($i - $windowSize >= 0) ? $prefixSum[$i - $windowSize] : 0;
            $sum = $prefixSum[$i] - $startSum;
            $result[] = $sum / $windowSize;
        }

        return $result;
    }
}
