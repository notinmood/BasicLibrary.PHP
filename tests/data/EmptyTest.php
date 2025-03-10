<?php
/**
 * @file   : EmptyTest.php
 * @time   : 7:18
 * @date   : 2022/1/13
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use PHPUnit\Framework\TestCase;

class EmptyTest extends TestCase
{
    public function testEqual()
    {
        $a      = false;
        $b      = null;
        $actual = false;
        if ($a === $b) {
            $actual = true;
        }
        $expected = false;
        self::assertEquals($expected, $actual);
    }

    public function getSome()
    {
        // {"key":"value",}
        // "([{}])";
    }
}
