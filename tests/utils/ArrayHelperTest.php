<?php
/**
 * @file   : ArrayHelperTest.php
 * @time   : 15:49
 * @date   : 2021/9/8
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use Hiland\Data\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function testIsContains(): void
    {
        // 验证索引数组
        $this->assertTrue(ArrayHelper::isContains([1, 2, 3], 2));
        $this->assertFalse(ArrayHelper::isContains([1, 2, 3], 4));
        $this->assertTrue(ArrayHelper::isContains(['a', 'b', 'c'], 'a'));
        $this->assertFalse(ArrayHelper::isContains(['a', 'b', 'c'], 'd'));
        $this->assertTrue(ArrayHelper::isContains([1.5, 2.5, 3.5], 2.5));
        $this->assertFalse(ArrayHelper::isContains([1.5, 2.5, 3.5], 4.5));

        // 验证关联数组
        $this->assertTrue(ArrayHelper::isContains(['key1' => 'value1', 'key2' => 'value2'], 'value1'));
        $this->assertFalse(ArrayHelper::isContains(['key1' => 'value1', 'key2' => 'value2'], 'value3'));
    }

    public function testIsContainsKey(): void
    {
        $this->assertTrue(ArrayHelper::isContainsKey(['key1' => 'value1', 'key2' => 'value2'], 'key1'));
        $this->assertFalse(ArrayHelper::isContainsKey(['key1' => 'value1', 'key2' => 'value2'], 'key3'));
    }

    public function testIsContainsValue(): void
    {
        $this->assertTrue(ArrayHelper::isContainsValue(['key1' => 'value1', 'key2' => 'value2'], 'value1'));
        $this->assertFalse(ArrayHelper::isContainsValue(['key1' => 'value1', 'key2' => 'value2'], 'value3'));
        $this->assertTrue(ArrayHelper::isContainsValue([1, 2, 3], 2));
        $this->assertFalse(ArrayHelper::isContainsValue([1, 2, 3], 4));
        $this->assertTrue(ArrayHelper::isContainsValue(['a', 'b', 'c'], 'a'));
        $this->assertFalse(ArrayHelper::isContainsValue(['a', 'b', 'c'], 'd'));
        $this->assertTrue(ArrayHelper::isContainsValue([1.5, 2.5, 3.5], 2.5));
        $this->assertFalse(ArrayHelper::isContainsValue([1.5, 2.5, 3.5], 4.5));
    }

    public function testGetItem(): void
    {
        // @formatter:off
        $data["city"]     = "qingdao";
        $data["provence"] = "shandong";
        // @formatter:on

        $actual   = ArrayHelper::getItem($data, 'city');
        $expected = 'qingdao';
        self::assertEquals($expected, $actual);

        $actual   = ArrayHelper::getItem($data, "street", "");
        $expected = "";
        self::assertEquals($expected, $actual);
    }

    public function testSort2D(): void
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

    public function testPush(): void
    {
        $targetArray = $this->prepareIndexArray();
        $actual      = ArrayHelper::push($targetArray, "X");
        $expected    = ['A', 'B', 'C', 'D', 'X'];
        self::assertEquals($expected, $actual);

        $targetArray = $this->prepareIndexArray();
        $actual      = ArrayHelper::push($targetArray, "X", "Y");
        $expected    = ['A', 'B', 'C', 'D', 'X', 'Y'];
        self::assertEquals($expected, $actual);
    }

    private function prepareIndexArray(): array
    {
        return ['A', 'B', 'C', 'D'];
    }

    public function testIsAssociateArray(): void
    {
        $targetArray = $this->prepareAssociateArray1();
        $actual      = ArrayHelper::isAssociateArray($targetArray);
        self::assertTrue($actual);

        $targetArray = $this->prepareIndexArray();
        $actual      = ArrayHelper::isAssociateArray($targetArray);
        self::assertFalse($actual);

        $targetArray = [];
        $actual      = ArrayHelper::isAssociateArray($targetArray);
        self::assertFalse($actual);

        $targetArray = null;
        $actual      = ArrayHelper::isAssociateArray($targetArray);
        self::assertFalse($actual);
    }

    private function prepareAssociateArray1(): array
    {
        $array1['a'] = "1A";
        $array1['b'] = "1B";
        $array1['c'] = "1C";
        return $array1;
    }

    public function testIsIndexArray(): void
    {
        $targetArray = $this->prepareIndexArray();
        $actual      = ArrayHelper::isIndexArray($targetArray);
        self::assertTrue($actual);

        $targetArray = $this->prepareAssociateArray1();
        $actual      = ArrayHelper::isIndexArray($targetArray);
        self::assertFalse($actual);

        $targetArray = [];
        $actual      = ArrayHelper::isIndexArray($targetArray);
        self::assertFalse($actual);

        $targetArray = null;
        $actual      = ArrayHelper::isIndexArray($targetArray);
        self::assertFalse($actual);
    }

    public function testRemoveHead(): void
    {
        $data          = $this->prepareAssociateArray1();
        $expected['b'] = "1B";
        $expected['c'] = "1C";

        $actual = ArrayHelper::removeHead($data);
        self::assertEquals($expected, $actual);
    }

    public function testRemoveTail(): void
    {
        $data          = $this->prepareAssociateArray1();
        $expected['a'] = "1A";
        $expected['b'] = "1B";

        $actual = ArrayHelper::removeTail($data);

        self::assertEquals($expected, $actual);
    }

    public function testRemoveIndex(): void
    {
        $data          = $this->prepareAssociateArray1();
        $expected['a'] = "1A";
        $expected['b'] = "1B";

        $actual = ArrayHelper::removeIndex($data, 2);

        self::assertEquals($expected, $actual);
    }

    public function testRemove(): void
    {
        /**
         * 目标数组中没有符合条件元素的情况
         */
        $data         = $this->prepareAssociateArray1();
        $actual       = ArrayHelper::removeItem($data, "22");
        $expect0['a'] = "1A";
        $expect0['b'] = "1B";
        $expect0['c'] = "1C";
        self::assertEquals($expect0, $actual);

        /**
         * 目标数组中有单个元素符合的情况
         */
        $data         = $this->prepareAssociateArray1();
        $actual       = ArrayHelper::removeItem($data, "1B");
        $expect1['a'] = "1A";
        $expect1['c'] = "1C";

        self::assertEquals($expect1, $actual);


        $data         = $this->prepareAssociateArray1();
        $actual       = ArrayHelper::removeItem($data, "1A");
        $expect2['b'] = "1B";
        $expect2['c'] = "1C";

        self::assertEquals($expect2, $actual);

        /**
         * 目标数组中有多个元素符合的情况(把多个值中第一个清除掉)
         */
        $data         = $this->prepareAssociateArray1();
        $data['d']    = "1B";
        $actual       = ArrayHelper::removeItem($data, "1B");
        $expect1['a'] = "1A";
        $expect1['c'] = "1C";
        $expect1['d'] = "1B";

        self::assertEquals($expect1, $actual);
    }

    public function testMerge(): void
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

    /**
     * @return array
     */
    private function prepareAssociateArray2(): array
    {
        $array2['c'] = "2C";
        $array2['d'] = "2D";
        $array2['e'] = "2E";
        return $array2;
    }

    public function testExchangeKeyValue(): void
    {
        $data           = $this->prepareAssociateArray1();
        $expected["1A"] = "a";
        $expected["1B"] = "b";
        $expected["1C"] = "c";
        $actual         = ArrayHelper::exchangeKeyValue($data);
        self::assertEquals($expected, $actual);
    }

    public function testSelect1(): void
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

    public function testSelect2(): void
    {
        $array = [
            ['website' => [['id' => 1], ['url' => 'reddit.com']]],
            ['website' => ['id' => 2, 'url' => 'twitter.com']],
            ['website' => ['id' => 3, 'url' => 'dev.to']],
        ];

        $actual   = ArrayHelper::select($array, 'website.url');
        $expected = ['reddit.com', 'twitter.com', 'dev.to'];
        self::assertEquals($expected, $actual);

        $actual   = ArrayHelper::select($array, '0.website.1.url', true);
        $expected = ['reddit.com'];
        self::assertEquals($expected, $actual);
    }

    public function testGetNode(): void
    {
        $array = [
            'mysql' => ['host' => '1', 'user' => 'reddit.com'],
            'mongodb' => ['host' => 2, 'url' => 'twitter.com'],
            'mssql' => ['id' => 3, 'address' => ["state" => "WA", "city" => "Redmond"]],
        ];

        $actual   = ArrayHelper::getNode($array, 'mysql');
        $expected = ['host' => '1', 'user' => 'reddit.com'];
        self::assertEquals($expected, $actual);

        $actual   = ArrayHelper::getNode($array, 'mysql.user', "root");
        $expected = 'reddit.com';
        self::assertEquals($expected, $actual);

        $actual   = ArrayHelper::getNode($array, 'mssql.address.city', "QD");
        $expected = 'Redmond';
        self::assertEquals($expected, $actual);

        $actual   = ArrayHelper::getNode($array, 'mssql.address.telephone', "110");
        $expected = '110';
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testFlatten1(): void
    {
        $array = [
            ['website' => ['id' => 1, 'url' => 'reddit.com']],
            ['website' => ['id' => 2, 'url' => 'twitter.com']],
            ['website' => ['id' => 3, 'url' => 'dev.to']],
        ];

        $actual = ArrayHelper::flatten($array);
        /** @noinspection all */
        $actual = json_encode($actual, JSON_THROW_ON_ERROR);


        $expected = '{"0.website.id":1,"0.website.url":"reddit.com","1.website.id":2,"1.website.url":"twitter.com","2.website.id":3,"2.website.url":"dev.to"}';

        self::assertEquals($expected, $actual);
    }

    public function testFlatten2(): void
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
        /** @noinspection all */
        $actual = json_encode($actual, JSON_THROW_ON_ERROR);

        $expected = '{"id":"82","remark":"hello","time":"2016-06-15 15:23:21","contact.id":"182","contact.name":"\u89e3\u7136","contact.phone":"18888888888"}';

        self::assertEquals($expected, $actual);
    }

    public function testFlatten3(): void
    {
        $array = [
            ['website' => [['id' => 1], ['url' => 'reddit.com']]],
        ];

        $actual = ArrayHelper::flatten($array, ".", "", "I_");
        /** @noinspection all */
        $actual = json_encode($actual, JSON_THROW_ON_ERROR);

        $expected = '{"I_0.website.I_0.id":1,"I_0.website.I_1.url":"reddit.com"}';

        self::assertEquals($expected, $actual);
    }

    public function testZip(): void
    {
        $a = [1, 3, 5, 7, 9];
        $b = [2, 4, 6, 8];
        $c = ["a", "b", "c"];

        $expected = [[1, 2, "a"], [3, 4, "b"], [5, 6, "c"]];
        $actual   = ArrayHelper::zip($a, $b, $c);
        self::assertEquals($expected, $actual);


        $d        = ["a", "b", "c", "d"];
        $expected = [[1, 2, "a"], [3, 4, "b"], [5, 6, "c"], [7, 8, "d"]];
        $actual   = ArrayHelper::zip($a, $b, $d);
        self::assertEquals($expected, $actual);
    }

    public function testContainsValue(): void
    {
        $array1          = ["age" => 20];
        $array1["email"] = "9727005@qq.com";
        $array1["name"]  = "zhangsan";

        $actual   = ArrayHelper::isContainsValue($array1, "zhangsan");
        self::assertTrue($actual);

        $actual   = ArrayHelper::isContainsValue($array1, "张三");
        self::assertFalse($actual);

        $array2   = ["beijing", "shanghai", "qingdao"];
        $actual   = ArrayHelper::isContainsValue($array2, "qingdao");
        self::assertTrue($actual);

        $actual   = ArrayHelper::isContainsValue($array2, "guangzhou");
        self::assertFalse($actual);
    }
}
