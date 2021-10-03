<?php
/**
 * @file   : StringHelperTest.php
 * @time   : 12:03
 * @date   : 2021/8/7
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\Utils\Data;

use Hiland\Utils\Data\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testIsEndWith()
    {
        $whole = "吴军信息传";
        $padding = "信息";
        $result = StringHelper::isEndWith($whole, $padding);
        $this->assertFalse($result);

        $padding = "息传";
        $result = StringHelper::isEndWith($whole, $padding);
        $this->assertTrue($result);
    }

    public function testIsStartWith()
    {
        $whole = "吴军信息传";
        $padding = "1吴军";
        $result = StringHelper::isStartWith($whole, $padding);
        $this->assertFalse($result);

        $padding = "吴军";
        $result = StringHelper::isStartWith($whole, $padding);
        $this->assertTrue($result);
    }

    public function testGrouping()
    {
        $data = "13573290346";
        $formator = "{3}-{4}-{4}";
        $actual = StringHelper::grouping($data, $formator);
        $expected = "135-7329-0346";
        self::assertEquals($expected, $actual);
    }

    public function testUpperWordsFirstChar()
    {
        $data = "I like this game!";
        $actual = StringHelper::upperWordsFirstChar($data);
        $expected = "I Like This Game!";
        self::assertEquals($expected, $actual);
    }

    public function testPaddingEnd()
    {
        $data = "qingdao";
        $actual = StringHelper::paddingEnd($data, 10, "-");
        $expected = "qingdao---";

        self::assertEquals($expected, $actual);
    }

    public function testPaddingBegin()
    {
        $data = "qingdao";
        $actual = StringHelper::paddingBegin($data, 10);
        $expected = "   qingdao";

        self::assertEquals($expected, $actual);
    }

    public function testReplace()
    {
        $data = "我是一个中国人,我骄傲!";
        $actual = StringHelper::replace($data, "我", "你");
        $expected = "你是一个中国人,你骄傲!";
        self::assertEquals($expected, $actual);

        $data = "我是一个中国人,我骄傲!";
        $actual = StringHelper::replace($data, "/我/i", "你",true);
        $expected = "你是一个中国人,你骄傲!";
        self::assertEquals($expected, $actual);

        $data = "I_I_0.website.id";
        $actual = StringHelper::replace($data, "/I_I_\d+/i", "",true);
        $expected = ".website.id";
        self::assertEquals($expected, $actual);
    }
}
