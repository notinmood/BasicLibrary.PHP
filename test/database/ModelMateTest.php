<?php
/**
 * @file   : ModelMateTest.php
 * @time   : 10:09
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\database;

use PHPUnit\Framework\TestCase;

/**
 * 对Mate的测试，都统一在 MateContainerTest.php 内进行。
 */
class ModelMateTest extends TestCase
{
    public function testIndex()
    {
        $actual = 1;
        $expected = 1;
        self::assertEquals($expected, $actual);
    }
}