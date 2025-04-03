<?php
/**
 * @file   : zip.php
 * @time   : 13:42
 * @date   : 2025/4/1
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\Pattern;

use Hiland\Data\ArrayHelper;

/**
 * 循环辅助类
 */
class LoopHelper
{
    /**
     * 分别提取每个数组的第n个元素组成新的二维数组(内层维度为每个传入数组的第n个元素组成的数组；外层维度为传入的最短数组的长度)
     * @param ...$arrayData array 传入的各个数组
     * @return array
     * @example
     *         $a = [1,3,5,7,9];
     *         $b= [2,4,6,8];
     *         $c= ["a","b","c"];
     *         $result= ArrayHelper::zip($a,$b,$c);
     *         ────────────────────────
     *         $result 的值为 [[1,2,"a"],[3,4,"b"],[5,6,"c"]];
     */
    public static function zip(...$arrayData): array
    {
        return ArrayHelper::zip(...$arrayData);
    }

    /**
     * 计算多个数组的笛卡尔积
     * @param array ...$arrays
     * @return array
     */
    public static function product(array ...$arrays): array {
        return ArrayHelper::product(...$arrays);
    }

}