<?php
/**
 * @file   : BoolHelperTest.php
 * @time   : 20:59
 * @date   : 2022/2/25
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Hiland\Data\BoolHelper;
use PHPUnit\Framework\TestCase;

class BoolHelperTest extends TestCase
{

    // public function testGetText()
    // {
    //
    // }
    //
    // public function testIsTrue()
    // {
    //
    // }

    // public function testIsRealTrue()
    // {
    //
    // }

    public function testIsRealFalse(): void
    {
        $actual = BoolHelper::isRealFalse(false);
        self::assertTrue($actual);
    }
}
