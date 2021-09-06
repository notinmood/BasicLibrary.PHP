<?php
/**
 * @file   : ObjectHelperTest.php
 * @time   : 11:02
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

use PHPUnit\Framework\TestCase;

class ObjectHelperTest extends TestCase
{

    public function testIsEmpty()
    {
        $actual= ObjectHelper::isEmpty("AAA");
        self::assertEquals(false, $actual);
    }
}
