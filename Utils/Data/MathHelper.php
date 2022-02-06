<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/26
 * Time: 9:17
 */

namespace Hiland\Utils\Data;


use Hiland\Utils\DataConstruct\Queue;

class MathHelper
{
    /**
     * 百分比转小数
     * @param $data string 百分比字符串（可以带%也可以不带）
     * @return float
     */
    public static function convertPercentToFloat(string $data)
    {
        return (float)$data / 100;
    }

    /**
     * 小数转百分比
     * @param     $data
     * @param int $precision 精度位数
     * @return string 带%的百分比字符串
     */
    public static function convertFloatToPercent($data, int $precision = 2): string
    {
        $format = "%01." . $precision . "f";
        return sprintf($format, $data * 100) . '%';
    }


    /**
     * 获取一个数列的移动平均值
     * @param array  $sourceArray     待计算的原始数组
     * @param int    $windowPeriod    滑动窗口的窗口大小(即时间周期,比如每5天作为一个周期做一次计算)
     * @param string $targetFieldName 如果是一维数组可以忽略本参数；如果是二维数组，请指定需要进行计算的字段名称。
     * @return array
     */
    public static function ma(array $sourceArray, int $windowPeriod, string $targetFieldName = ''): array
    {
        return self::rolling($sourceArray, $windowPeriod, $targetFieldName, [self::class, "_maCallback"]);
    }

    /**
     * 对数组进行窗口滑动计算
     * @param array    $sourceArray             待计算的原始数组
     * @param int      $windowPeriod            滑动窗口的窗口大小(即时间周期,比如每5天作为一个周期做一次计算)
     * @param string   $targetFieldName         如果是一维数组可以忽略本参数；如果是二维数组，请指定需要进行计算的字段名称。
     * @param callable $everyWindowCallbackFunc 对每个窗口周期进行计算的回调函数(传递出1个参数：包含当前窗口内的各个元素的array)
     * @return array
     */
    public static function rolling(array $sourceArray, int $windowPeriod, string $targetFieldName = '', callable $everyWindowCallbackFunc): array
    {
        $result = null;
        $level  = ArrayHelper::getLevel($sourceArray);
        $pIndex = $windowPeriod - 1;
        $data   = array_values($sourceArray);
        $sum    = 0;

        $queue = new Queue();

        foreach ($data as $k => $v) {
            $needRemoveValue = 0;

            if ($level == 1) {
                $currentValue = $v;
                if (($k - $pIndex) >= 0) {
                    $needRemoveValue = $data[$k - $pIndex];
                }
            } else {
                $currentValue = $v[$targetFieldName];
                if (($k - $pIndex) >= 0) {
                    $needRemoveValue = $data[$k - $pIndex][$targetFieldName];
                }
            }

            // $sum += $currentValue;
            $queue->push($currentValue);
            if ($k < $pIndex) {
                $item = 0;
            } else {
                $item = $everyWindowCallbackFunc($queue->convertToArray());
                $queue->pop($needRemoveValue);
            }

            $result[] = $item;
        }

        return $result;
    }

    private static function _maCallback($windowData): string
    {
        $sum = 0;
        foreach ($windowData as $item) {
            $sum += $item;
        }

        $count = count($windowData);

        return sprintf("%.2f", ($sum / $count));
    }

    /**
     * 数学中各种进制之间的相互转换,返回字符串表示的数据(convertBase的别名缩写)
     * @param string $numberString 待转换的(字符串格式的)目标数字
     * @param int    $fromBase     从**进制转换
     * @param int    $toBase       转换到**进制
     * @return string
     */
    public function convert(string $numberString, int $fromBase, int $toBase): string
    {
        return self::convertBase($numberString, $fromBase, $toBase);
    }

    /**
     * 数学中各种进制之间的相互转换,返回字符串表示的数据
     * @param string $numberString 待转换的(字符串格式的)目标数字
     * @param int    $fromBase     从**进制转换
     * @param int    $toBase       转换到**进制
     * @return string
     */
    public static function convertBase(string $numberString, int $fromBase, int $toBase): string
    {
        return base_convert($numberString, $fromBase, $toBase);
    }
}