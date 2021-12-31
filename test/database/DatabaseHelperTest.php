<?php
/**
 * @file   : DatabaseHelperTest.php
 * @time   : 14:35
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{
    public function testWrapData()
    {
        $actual = DatabaseHelper::wrapSqlValue("qingdao");
        $expected = "'qingdao'";
        self::assertEquals($expected, $actual);

        $actual = DatabaseHelper::wrapSqlValue(true);
        $expected = true;
        self::assertEquals($expected, $actual);
    }
}
