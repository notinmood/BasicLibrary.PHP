<?php
/**
 * @file   : StringHelperTest.php
 * @time   : 12:03
 * @date   : 2021/8/7
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Hiland\Data\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testGetPositions()
    {
        $whole    = "山东省枣庄市滕州市城市建设纲要";
        $sub      = "滕州市";
        $actual   = StringHelper::getPositions($whole, $sub);
        $expected = [6];
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::getPositions($whole, $sub, true);
        $expected = [6];
        self::assertEquals($expected, $actual);


        $whole    = "山东省枣庄市滕州市城市建设纲要";
        $sub      = "市";
        $actual   = StringHelper::getPositions($whole, $sub);
        $expected = [5, 8, 10];
        self::assertEquals($expected, $actual);


        $whole    = "StringHelper::getPosition";
        $sub      = "e";
        $actual   = StringHelper::getPositions($whole, $sub);
        $expected = [7, 10, 15];
        self::assertEquals($expected, $actual);

        $whole    = "StringHelper::getPosition";
        $sub      = "p";
        $actual   = StringHelper::getPositions($whole, $sub);
        $expected = [9];
        self::assertEquals($expected, $actual);

        $whole    = "StringHelper::getPosition";
        $sub      = "p";
        $actual   = StringHelper::getPositions($whole, $sub, true);
        $expected = [9, 17];
        self::assertEquals($expected, $actual);
    }

    public function testGetFirstPosition()
    {
        $whole    = "StringHelper::getPosition";
        $sub      = "p";
        $actual   = StringHelper::getFirstPosition($whole, $sub, true);
        $expected = 9;
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::getFirstPosition($whole, $sub, false);
        $expected = 9;
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::getFirstPosition($whole, $sub, true, true);
        $expected = 17;
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::getFirstPosition($whole, $sub, false, true);
        $expected = 9;
        self::assertEquals($expected, $actual);
    }

    public function testIsEndWith()
    {
        $whole   = "吴军信息传";
        $padding = "信息";
        $result  = StringHelper::isEndWith($whole, $padding);
        $this->assertFalse($result);

        $padding = "息传";
        $result  = StringHelper::isEndWith($whole, $padding);
        $this->assertTrue($result);
    }

    public function testIsStartWith()
    {
        $whole   = "吴军信息传";
        $padding = "1吴军";
        $result  = StringHelper::isStartWith($whole, $padding);
        $this->assertFalse($result);

        $padding = "吴军";
        $result  = StringHelper::isStartWith($whole, $padding);
        $this->assertTrue($result);
    }

    public function testGrouping()
    {
        $data      = "13573290346";
        $formatter = "{3}-{4}-{4}";
        $actual    = StringHelper::grouping($data, $formatter);
        $expected  = "135-7329-0346";
        self::assertEquals($expected, $actual);
    }

    public function testUpperWordsFirstChar()
    {
        $data     = "I like this game!";
        $actual   = StringHelper::upperWordsFirstChar($data);
        $expected = "I Like This Game!";
        self::assertEquals($expected, $actual);
    }

    public function testPaddingEnd()
    {
        $data     = "qingdao";
        $actual   = StringHelper::paddingEnd($data, 10, "-");
        $expected = "qingdao---";

        self::assertEquals($expected, $actual);
    }

    public function testPaddingBegin()
    {
        $data     = "qingdao";
        $actual   = StringHelper::paddingBegin($data, 10);
        $expected = "   qingdao";

        self::assertEquals($expected, $actual);
    }

    public function testReplace()
    {
        $data     = "我是一个中国人,我骄傲!";
        $actual   = StringHelper::replace($data, "我", "你");
        $expected = "你是一个中国人,你骄傲!";
        self::assertEquals($expected, $actual);

        $data     = "我是一个中国人,我骄傲!";
        $actual   = StringHelper::replace($data, "/我/i", "你", true);
        $expected = "你是一个中国人,你骄傲!";
        self::assertEquals($expected, $actual);

        $data     = "I_I_0.website.id";
        $actual   = StringHelper::replace($data, "/I_I_\d+/i", "", true);
        $expected = ".website.id";
        self::assertEquals($expected, $actual);
    }

    public function testConvertFromUTF8ToUnicode()
    {
        $data     = "我";
        $actual   = StringHelper::convertUTF8ToUnicode($data);
        $expected = "6211";

        self::assertEquals($expected, $actual);
    }

    public function testConvertFromUnicodeToUTF8()
    {
        $data     = "6211";
        $actual   = StringHelper::convertUnicodeToUTF8($data);
        $expected = "我";

        self::assertEquals($expected, $actual);
    }

    public function testRemoveHead()
    {
        $whole    = "i love china!";
        $actual   = StringHelper::removeHead($whole, 3);
        $expected = "ove china!";
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::removeHead($whole, 13);
        $expected = "";
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::removeHead($whole, 14);
        $expected = "";
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::removeHead($whole, "i lo");
        $expected = "ve china!";
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::removeHead($whole, "i lol");
        $expected = "i love china!";
        self::assertEquals($expected, $actual);
    }

    public function testRemoveTail()
    {
        $whole    = "i love china!";
        $actual   = StringHelper::removeTail($whole, 3);
        $expected = "i love chi";
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::removeTail($whole, 13);
        $expected = "";
        self::assertEquals($expected, $actual);

        $actual   = StringHelper::removeTail($whole, 14);
        $expected = "";
        self::assertEquals($expected, $actual);

        $whole    = "i love你中国!";
        $actual   = StringHelper::removeTail($whole, 3);
        $expected = "i love你";
        self::assertEquals($expected, $actual);

        $whole    = "i love你中国!";
        $actual   = StringHelper::removeTail($whole, "国!");
        $expected = "i love你中";
        self::assertEquals($expected, $actual);

        $whole    = "i love你中国!";
        $actual   = StringHelper::removeTail($whole, "中国");
        $expected = "i love你中国!";
        self::assertEquals($expected, $actual);
    }

    public function testFormat()
    {
        $actual   = StringHelper::format("{?}喜欢{?}.", "解大劦", "qingdao");
        $expected = "解大劦喜欢qingdao.";
        self::assertEquals($expected, $actual);
    }

    public function testSplice()
    {
        $data     = "i love China.";
        $od       = " ";
        $nd       = "|";
        $actual   = StringHelper::splice($data, $od, $nd);
        $expected = "i|love|China.";
        self::assertEquals($expected, $actual);

        $od       = ".";
        $nd       = "!";
        $actual   = StringHelper::splice($data, $od, $nd);
        $expected = "i love China!";
        self::assertEquals($expected, $actual);
    }

    /**
     * 测试 getStringBeforeSeparator 方法 - 正常情况
     */
    public function testGetStringBeforeSeparatorNormal()
    {
        $this->assertEquals('测试', StringHelper::getStringBeforeSeparator('测试_字符串', '_'));
        $this->assertEquals('hello', StringHelper::getStringBeforeSeparator('hello world', ' world'));
    }

    /**
     * 测试 getStringBeforeSeparator 方法 - 分隔符不存在情况
     */
    public function testGetStringBeforeSeparatorSeparatorNotFound()
    {
        $this->assertEquals('测试字符串', StringHelper::getStringBeforeSeparator('测试字符串', '_'));
    }

    /**
     * 测试 getStringBeforeSeparator 方法 - 空字符情况
     */
    public function testGetStringBeforeSeparatorEmpty()
    {
        $this->assertEquals('', StringHelper::getStringBeforeSeparator('', '_'));
        $this->assertEquals('', StringHelper::getStringBeforeSeparator('测试字符串', '测试字符串'));
    }

    /**
     * 测试 getStringAfterSeparator 方法 - 正常情况
     */
    public function testGetStringAfterSeparatorNormal()
    {
        $this->assertEquals('字符串', StringHelper::getStringAfterSeparator('测试_字符串', '_'));
        $this->assertEquals('world', StringHelper::getStringAfterSeparator('hello world', ' '));
    }

    /**
     * 测试 getStringAfterSeparator 方法 - 分隔符不存在情况
     */
    public function testGetStringAfterSeparatorSeparatorNotFound()
    {
        $this->assertEquals('测试字符串', StringHelper::getStringAfterSeparator('测试字符串', '_'));
    }

    /**
     * 测试 getStringAfterSeparator 方法 - 空字符情况
     */
    public function testGetStringAfterSeparatorEmpty()
    {
        $this->assertEquals('', StringHelper::getStringAfterSeparator('', '_'));
        $this->assertEquals('测试字符串', StringHelper::getStringAfterSeparator('测试字符串', ''));
    }

    /**
     * 测试 getStringAfterSeparator 方法 - 分隔符为空字符串情况
     */
    public function testGetStringAfterSeparatorEmptySeparator()
    {
        $this->assertEquals('测试字符串', StringHelper::getStringAfterSeparator('测试字符串', ''));
    }


}
