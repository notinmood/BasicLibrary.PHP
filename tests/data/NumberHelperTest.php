<?php
/**
 * @file   : NumberHelperTest.php
 * @time   : 10:06
 * @date   : 2021/9/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Hiland\Data\NumberHelper;
use PHPUnit\Framework\TestCase;

class NumberHelperTest extends TestCase
{
    public function testFormat()
    {
        $number   = "1234567.890";
        $actual   = NumberHelper::format($number, 1);
        $expected = "1,234,567.9";
        self::assertEquals($expected, $actual);
    }
}
