<?php
/**
 * @file   : MateContainerTest.php
 * @time   : 21:09
 * @date   : 2021/12/30
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\database;

use Hiland\Utils\DataModel\MateContainer;
use PHPUnit\Framework\TestCase;

class MateContainerTest extends TestCase
{
    public function testGet()
    {
        $mate = MateContainer::get("user");
        $entity = $mate->get(1);

        $actual = $entity["name"];
        $expected = "zhangsan";
        self::assertEquals($expected, $actual);
    }
}
