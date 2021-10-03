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
    public function testSort2D()
    {
        $myArray = [
            [
                "地区" => "河南",
                "2010年" => 5437.1,
                "2011年" => 5542.5,
                "2012年" => 5638.6,
            ],
            [
                "地区" => "黑龙江",
                "2010年" => 5012.8,
                "2011年" => 5570.6,
                "2012年" => 5761.5,
            ],
            [
                "地区" => "山东",
                "2010年" => 4335.7,
                "2011年" => 4426.3,
                "2012年" => 4511.4,
            ],
        ];

        $actual = ArrayHelper::sort2D($myArray, "2011年");

        self::assertEquals("山东", $actual[0]["地区"]);
        self::assertEquals("河南", $actual[1]["地区"]);
        self::assertEquals("黑龙江", $actual[2]["地区"]);
    }

    public function testPush()
    {
        $targetArray = $this->prepareIndexArray();
        $actual = ArrayHelper::push($targetArray, "X");
        $expected = ['A', 'B', 'C', 'D', 'X'];
        self::assertEquals($expected, $actual);

        $targetArray = $this->prepareIndexArray();
        $actual = ArrayHelper::push($targetArray, "X", "Y");
        $expected = ['A', 'B', 'C', 'D', 'X', 'Y'];
        self::assertEquals($expected, $actual);
    }


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

    public function testRemoveHead()
    {
        $data = $this->prepareAssociateArray1();
        $expected['b'] = "1B";
        $expected['c'] = "1C";

        $actual = ArrayHelper::removeHead($data);
        self::assertEquals($expected, $actual);
    }

    public function testRemoveTail()
    {
        $data = $this->prepareAssociateArray1();
        $expected['a'] = "1A";
        $expected['b'] = "1B";

        $actual = ArrayHelper::removeTail($data);

        self::assertEquals($expected, $actual);
    }

    public function testRemoveIndex()
    {
        $data = $this->prepareAssociateArray1();
        $expected['a'] = "1A";
        $expected['b'] = "1B";

        $actual = ArrayHelper::removeIndex($data, 2);

        self::assertEquals($expected, $actual);
    }

    public function testRemove()
    {
        /**
         * 目标数组中没有符合条件元素的情况
         */
        $data = $this->prepareAssociateArray1();
        $actual = ArrayHelper::removeItem($data, "22");
        $expect0['a'] = "1A";
        $expect0['b'] = "1B";
        $expect0['c'] = "1C";
        self::assertEquals($expect0, $actual);

        /**
         * 目标数组中有单个元素符合的情况
         */
        $data = $this->prepareAssociateArray1();
        $actual = ArrayHelper::removeItem($data, "1B");
        $expect1['a'] = "1A";
        $expect1['c'] = "1C";

        self::assertEquals($expect1, $actual);

        /**
         * 目标数组中有多个元素符合的情况(把多个值中第一个清除掉)
         */
        $data = $this->prepareAssociateArray1();
        $data['d'] = "1B";
        $actual = ArrayHelper::removeItem($data, "1B");
        $expect1['a'] = "1A";
        $expect1['c'] = "1C";
        $expect1['d'] = "1B";

        self::assertEquals($expect1, $actual);
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

    public function testExchangeKeyValue()
    {
        $data = $this->prepareAssociateArray1();
        $expected["1A"] = "a";
        $expected["1B"] = "b";
        $expected["1C"] = "c";
        $actual = ArrayHelper::exchangeKeyValue($data);
        self::assertEquals($expected, $actual);
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

    public function testSelect1()
    {
        $array = [
            ['website' => ['id' => 1, 'url' => 'reddit.com']],
            ['website' => ['id' => 2, 'url' => 'twitter.com']],
            ['website' => ['id' => 3, 'url' => 'dev.to']],
        ];

        $actual = ArrayHelper::select($array, 'website.url');

        $expected = ['reddit.com', 'twitter.com', 'dev.to'];
        self::assertEquals($expected, $actual);
    }

    public function testSelect2()
    {
        $array = [
            ['website' => [['id' => 1], ['url' => 'reddit.com']]],
            ['website' => ['id' => 2, 'url' => 'twitter.com']],
            ['website' => ['id' => 3, 'url' => 'dev.to']],
        ];

        $actual = ArrayHelper::select($array, 'website.url');
        $expected = ['reddit.com', 'twitter.com', 'dev.to'];
        self::assertEquals($expected, $actual);

        $actual = ArrayHelper::select($array, '0.website.1.url', true);
        $expected = ['reddit.com'];
        self::assertEquals($expected, $actual);
    }

    public function testFlatten1()
    {
        $array = [
            ['website' => ['id' => 1, 'url' => 'reddit.com']],
            ['website' => ['id' => 2, 'url' => 'twitter.com']],
            ['website' => ['id' => 3, 'url' => 'dev.to']],
        ];

        $actual = ArrayHelper::flatten($array);
        $actual = json_encode($actual);

        $expected = '{"0.website.id":1,"0.website.url":"reddit.com","1.website.id":2,"1.website.url":"twitter.com","2.website.id":3,"2.website.url":"dev.to"}';

        self::assertEquals($expected, $actual);
    }

    public function testFlatten2()
    {
        $array =
            [
                "id" => "82",
                "remark" => 'hello',
                "time" => "2016-06-15 15:23:21",
                "contact" =>
                    [
                        "id" => "182",
                        "name" => "解然",
                        "phone" => "18888888888",
                    ],
            ];


        $actual = ArrayHelper::flatten($array, '.');
        $actual = json_encode($actual);

        $expected = '{"id":"82","remark":"hello","time":"2016-06-15 15:23:21","contact.id":"182","contact.name":"\u89e3\u7136","contact.phone":"18888888888"}';

        self::assertEquals($expected, $actual);
    }

    public function testFlatten3()
    {
        $array = [
            ['website' => [['id' => 1], ['url' => 'reddit.com']]],
        ];

        $actual = ArrayHelper::flatten($array, ".", "", "I_");
        $actual = json_encode($actual);

        $expected = '{"I_0.website.I_0.id":1,"I_0.website.I_1.url":"reddit.com"}';

        self::assertEquals($expected, $actual);
    }
}
