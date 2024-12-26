<?php
/**
 * @file   : ByteHelperTest.php
 * @time   : 21:14
 * @date   : 2022/2/25
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use Hiland\Data\ByteHelper;
use PHPUnit\Framework\TestCase;

class ByteHelperTest extends TestCase
{

    public function testDisplayFriendly()
    {
        $value    = 1048;
        $actual   = ByteHelper::displayFriendly($value);
        $expected = "1.02KB";
        self::assertEquals($expected, $actual);

        $value    = 9635865;
        $actual   = ByteHelper::displayFriendly($value, " ");
        $expected = "9.19 MB";
        self::assertEquals($expected, $actual);
    }

    public function testConvert()
    {
        $value    = "I love qingdao city!";
        $actual   = $value;
        $expected = ByteHelper::convertFromString($value);
        $expected = ByteHelper::convertToString($expected);
        self::assertEquals($expected, $actual);
    }
}
