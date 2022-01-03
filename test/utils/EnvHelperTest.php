<?php
/**
 * @file   : EnvHelperTest.php
 * @time   : 19:26
 * @date   : 2021/12/30
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test;

use Hiland\Utils\Environment\EnvHelper;
use PHPUnit\Framework\TestCase;

class EnvHelperTest extends TestCase
{
    public function testGetRootPhysicalPath()
    {
        $actual = EnvHelper::getRootPhysicalPath();
        $expected = dirname(dirname(__DIR__));
        self::assertEquals($expected, $actual);
    }

}