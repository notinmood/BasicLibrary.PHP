<?php
/**
 * @file   : ArrayHelperTest.php
 * @time   : 15:49
 * @date   : 2021/9/8
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function testIsAssociateArray()
    {
        $targetArray = $this->prepareAssociateArray1();
        $actual = ArrayHelper::isAssociateArray($targetArray);
        self::assertEquals(true, $actual);

        $targetArray = $this->prepareIndexArray();
        $actual = ArrayHelper::isAssociateArray($targetArray);
        self::assertEquals(false, $actual);

        $targetArray = [];
        $actual = ArrayHelper::isAssociateArray($targetArray);
        self::assertEquals(false, $actual);

        $targetArray = null;
        $actual = ArrayHelper::isAssociateArray($targetArray);
        self::assertEquals(false, $actual);
    }

    public function testIsIndexArray()
    {
        $targetArray = $this->prepareIndexArray();
        $actual = ArrayHelper::isIndexArray($targetArray);
        self::assertEquals(true, $actual);

        $targetArray = $this->prepareAssociateArray1();
        $actual = ArrayHelper::isIndexArray($targetArray);
        self::assertEquals(false, $actual);

        $targetArray = [];
        $actual = ArrayHelper::isIndexArray($targetArray);
        self::assertEquals(false, $actual);

        $targetArray = null;
        $actual = ArrayHelper::isIndexArray($targetArray);
        self::assertEquals(false, $actual);
    }

    public function testRemove()
    {
        /**
         * 目标数组中没有符合条件元素的情况
         */
        $data = $this->prepareAssociateArray1();
        $actual = ArrayHelper::remove($data, "22");
        $expect0['a'] = "1A";
        $expect0['b'] = "1B";
        $expect0['c'] = "1C";
        self::assertEquals($expect0, $actual);

        /**
         * 目标数组中有单个元素符合的情况(把多个值都清除掉)
         */
        $data = $this->prepareAssociateArray1();
        $data['d'] = "1B";
        $actual = ArrayHelper::remove($data, "1B");
        $expect1['a'] = "1A";
        $expect1['c'] = "1C";
        self::assertEquals($expect1, $actual);

        /**
         * 移除一批元素
         */
        $data = $this->prepareAssociateArray1();
        $actual = ArrayHelper::remove($data, "1B", "1C");
        $expect2['a'] = "1A";
        self::assertEquals($expect2, $actual);
    }

    public function testMerge()
    {
        /**
         * 验证有重复key的合并情况
         */
        $array1 = $this->prepareAssociateArray1();
        $array2 = $this->prepareAssociateArray2();

        $expect['a'] = "1A";
        $expect['b'] = "1B";
        $expect['c'] = "2C";
        $expect['d'] = "2D";
        $expect['e'] = "2E";

        $actual = ArrayHelper::merge($array1, $array2);
        self::assertEquals($expect, $actual);

        /**
         *对传入数据有null的情况下的判断
         */
        $array3 = null;
        $actual = ArrayHelper::merge($array3, $array1);
        $expect = $array1;
        self::assertEquals($expect, $actual);
    }

    private function prepareAssociateArray1()
    {
        $array1['a'] = "1A";
        $array1['b'] = "1B";
        $array1['c'] = "1C";
        return $array1;
    }

    /**
     * @return array
     */
    private function prepareAssociateArray2()
    {
        $array2['c'] = "2C";
        $array2['d'] = "2D";
        $array2['e'] = "2E";
        return $array2;
    }

    private function prepareIndexArray()
    {
        return ['A', 'B', 'C', 'D'];
    }
}
