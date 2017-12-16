<?php
namespace Hiland\Biz\Misc;

/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/28
 * Time: 16:40
 */
class RedPacketHelper
{
    /**
     *
     * @param $totalAmount int 红包总额(因为为整数，金额请转换成分表示)
     * @param $bonusCount int 红包个数
     * @param $bonusMax int 每个小红包的最大额(因为为整数，金额请转换成分表示)
     * @param $bonusMin int 每个小红包的最小额(因为为整数，金额请转换成分表示)
     * @return array 存放生成的每个小红包的值的一维数组
     */
    public static function getBonus($totalAmount, $bonusCount, $bonusMax, $bonusMin)
    {
        $result = array();

        $average = $totalAmount / $bonusCount;

        $a = $average - $bonusMin;
        $b = $bonusMax - $bonusMin;

        //
        //这样的随机数的概率实际改变了，产生大数的可能性要比产生小数的概率要小。
        //这样就实现了大部分红包的值在平均数附近。大红包和小红包比较少。
        $range1 = self::sqr($average - $bonusMin);
        $range2 = self::sqr($bonusMax - $average);

        for ($i = 0; $i < $bonusCount; $i++) {
            //因为小红包的数量通常是要比大红包的数量要多的，因为这里的概率要调换过来。
            //当随机数>平均值，则产生小红包
            //当随机数<平均值，则产生大红包
            if (rand($bonusMin, $bonusMax) > $average) {
                // 在平均线上减钱
                $temp = $bonusMin + self::xRandom($bonusMin, $average);
                $result[$i] = $temp;
                $totalAmount -= $temp;
            } else {
                // 在平均线上加钱
                $temp = $bonusMax - self::xRandom($average, $bonusMax);
                $result[$i] = $temp;
                $totalAmount -= $temp;
            }
        }
        // 如果还有余钱，则尝试加到小红包里，如果加不进去，则尝试下一个。
        while ($totalAmount > 0) {
            for ($i = 0; $i < $bonusCount; $i++) {
                if ($totalAmount > 0 && $result[$i] < $bonusMax) {
                    $result[$i]++;
                    $totalAmount--;
                }
            }
        }
        // 如果钱是负数了，还得从已生成的小红包中抽取回来
        while ($totalAmount < 0) {
            for ($i = 0; $i < $bonusCount; $i++) {
                if ($totalAmount < 0 && $result[$i] > $bonusMin) {
                    $result[$i]--;
                    $totalAmount++;
                }
            }
        }
        return $result;
    }

    /**
     * 求一个数的平方
     * @param $n
     * @return mixed
     */
    private static function sqr($n)
    {
        return $n * $n;
    }

    /**
     * 生产min和max之间的随机数，但是概率不是平均的，从min到max方向概率逐渐加大。
     * 先平方，然后产生一个平方值范围内的随机数，再开方，这样就产生了一种“膨胀”再“收缩”的效果。
     * @param $min int
     * @param $max int
     * @return float
     */
    private static function xRandom($min, $max)
    {
        $sqr = intval(self:: sqr($max - $min));
        $rand_num = rand(0, ($sqr - 1));
        return intval(sqrt($rand_num));
    }
}