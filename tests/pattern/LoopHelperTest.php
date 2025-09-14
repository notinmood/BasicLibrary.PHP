<?php
/**
 * @file   : LoopHelperTest.php
 * @time   : 14:00
 * @date   : 2025/4/1
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\Test\pattern;

use Hiland\Pattern\LoopHelper;
use PHPUnit\Framework\TestCase;

class LoopHelperTest extends TestCase
{
    /**
     * 测试 zip 方法的正常情况
     */
    public function testZipHappyPath(): void
    {
        $a = [1, 3, 5, 7, 9];
        $b = [2, 4, 6, 8];
        $c = ["a", "b", "c"];
        $result = LoopHelper::zip($a, $b, $c);
        $expected = [[1, 2, "a"], [3, 4, "b"], [5, 6, "c"]];
        $this->assertEquals($expected, $result);
    }

    /**
     * 测试 zip 方法当传入空数组时的情况
     */
    public function testZipWithEmptyArray(): void
    {
        $a = [];
        $b = [2, 4, 6, 8];
        $c = ["a", "b", "c"];
        $result = LoopHelper::zip($a, $b, $c);
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    /**
     * 测试 zip 方法当传入的都是空数组时的情况
     */
    public function testZipWithAllEmptyArrays(): void
    {
        $a = [];
        $b = [];
        $c = [];
        $result = LoopHelper::zip($a, $b, $c);
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    /**
     * 测试 dealLargeDataUsingBatch 方法的正常情况
     */
    public function testDealLargeDataUsingBatchHappyPath(): void
    {
        $largeData = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $batchSize = 3;
        $output = '';
        $dealBatch = static function($batchData) use (&$output){
            $output .= "处理批次数据：" . implode(",", $batchData) . "\n";
        };

        LoopHelper::dealLargeDataUsingBatch($largeData, $batchSize, $dealBatch);
        $expected = "处理批次数据：1,2,3\n处理批次数据：4,5,6\n处理批次数据：7,8,9\n处理批次数据：10\n";
        $this->assertEquals($expected, $output);
    }

    /**
     * 测试 dealLargeDataUsingBatch 方法当 batchSize 为 0 时的情况
     */
    public function testDealLargeDataUsingBatchWithZeroBatchSize(): void
    {
        $largeData = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $batchSize = 0;
        $output = '';
        $dealBatch = static function($batchData) use (&$output){
            $output .= "处理批次数据：" . implode(",", $batchData) . "\n";
        };

        LoopHelper::dealLargeDataUsingBatch($largeData, $batchSize, $dealBatch);
        $expected = "";
        $this->assertEquals($expected, $output);
    }

    /**
     * 测试 dealLargeDataUsingBatch 方法当 batchSize 为 0 时的情况
     */
    public function testDealLargeDataUsingBatchWithOneBatchSize(): void
    {
        $largeData = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $batchSize = 1;
        $output = '';
        $dealBatch = static function($batchData) use (&$output){
            $output .= "处理批次数据：" . implode(",", $batchData) . "\n";
        };

        LoopHelper::dealLargeDataUsingBatch($largeData, $batchSize, $dealBatch);
        $expected = "处理批次数据：1\n处理批次数据：2\n处理批次数据：3\n处理批次数据：4\n处理批次数据：5\n处理批次数据：6\n处理批次数据：7\n处理批次数据：8\n处理批次数据：9\n处理批次数据：10\n";
        $this->assertEquals($expected, $output);
    }

    /**
     * 测试 dealLargeDataUsingBatch 方法当 batchSize 大于数组长度时的情况
     */
    public function testDealLargeDataUsingBatchWithLargeBatchSize(): void
    {
        $largeData = [1, 2, 3, 4, 5];
        $batchSize = 10;
        $output = '';
        $dealBatch = static function($batchData) use (&$output){
            $output .= "处理批次数据：" . implode(",", $batchData) . "\n";
        };

        LoopHelper::dealLargeDataUsingBatch($largeData, $batchSize, $dealBatch);
        $expected = "处理批次数据：1,2,3,4,5\n";
        $this->assertEquals($expected, $output);
    }

    /**
     * 测试 dealLargeDataUsingBatch 方法当传入空数组时的情况
     */
    public function testDealLargeDataUsingBatchWithEmptyArray(): void
    {
        $largeData = [];
        $batchSize = 3;
        $output = '';
        $dealBatch = static function($batchData) use (&$output){
            $output .= "处理批次数据：" . implode(",", $batchData) . "\n";
        };

        LoopHelper::dealLargeDataUsingBatch($largeData, $batchSize, $dealBatch);
        $expected = '';
        $this->assertEquals($expected, $output);
    }
}
