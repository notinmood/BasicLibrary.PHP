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

use Closure;
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
    public static function product(array ...$arrays): array
    {
        return ArrayHelper::product(...$arrays);
    }

    /**
     * @param array $largeData 带处理的大数据
     * @param int $batchSize 每个批次大小
     * @param Closure $dealBatchFunc 处理批次数据的回调函数，该函数接收一个数组作为参数，数组的元素个数与 $batchSize 相同
     * @return void
     * @example
     *         $largeData = [1,2,3,4,5,6,7,8,9,10];
     *         $batchSize = 3;
     *         $dealBatch = function($batchData){
     *             // 处理批次数据
     *             // 假设这里插入数据库
     *             echo "处理批次数据：". implode(",", $batchData). "<br>";
     *         };
     *         LoopHelper::dealLargeDataUsingBatch($largeData, $batchSize, $dealBatch);
     *         ─
     *         输出：
     *         处理批次数据：1,2,3
     *         处理批次数据：4,5,6
     *         处理批次数据：7,8,9
     *         处理批次数据：10
     *         说明：该函数将 $largeData 分成多个批次，每个批次的大小为 $batchSize，然后调用 $dealBatch 回调函数处理每个批次的数据。
     *         批次处理完成后，如果最后一批数据不足一个批次大小，则会单独处理。
     */
    public static function dealLargeDataUsingBatch(array $largeData, int $batchSize, Closure $dealBatchFunc): void
    {
        // 批次大小不能为0，否则直接返回跳出，什么都不做
        if ($batchSize === 0) {
            return;
        }

        $currentBatchData = [];
        foreach ($largeData as $row) {
            $currentBatchData[] = $row;

            // A-> 满足批次要求的时候，就进行批次处理阶段
            if (count($currentBatchData) >= $batchSize) {
                // 处理批次（比如插入数据库）
                $dealBatchFunc($currentBatchData);
                $currentBatchData = [];
            }
        }

        // B-> 处理最后一批不满足一个批次大小的“零散”数据
        if (count($currentBatchData) > 0) {
            $dealBatchFunc($currentBatchData);
        }
    }
}