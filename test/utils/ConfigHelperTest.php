<?php
/**
 * @file   : ConfigHelperTest.php
 * @time   : 21:40
 * @date   : 2021/9/5
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

use PHPUnit\Framework\TestCase;

class ConfigHelperTest extends TestCase
{
    public function testGet()
    {
        $key = "d.dA";
        $actual = ConfigHelper::get("$key");
        $expect = "dA-content";
        self::assertEquals($expect, $actual);

        $key = "archive.host";
        $actual = ConfigHelper::get("$key",null,"demo.config.ini");
        $expect = "localhost";
        self::assertEquals($expect, $actual);

        $key = "archive.database";
        $actual = ConfigHelper::get("$key",null);
        $expect = "archive";
        self::assertEquals($expect, $actual);
    }
}
