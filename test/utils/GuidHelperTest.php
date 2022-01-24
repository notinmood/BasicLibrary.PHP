<?php
/**
 * @file   : GuidHelperTest.php
 * @time   : 15:29
 * @date   : 2022/1/13
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

use PHPUnit\Framework\TestCase;

class GuidHelperTest extends TestCase
{
    public function testNewGuid()
    {
        $actual = getLength(GuidHelper::newGuid());
        $expected = 36;
        self::assertEquals($expected, $actual);
    }
}