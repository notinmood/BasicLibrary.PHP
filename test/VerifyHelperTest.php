<?php
/**
 * @file   : VerifyHelperTest.php
 * @time   : 20:10
 * @date   : 2021/9/11
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

use PHPUnit\Framework\TestCase;

class VerifyHelperTest extends TestCase
{

    public function testIsEmail()
    {
        $data = "9727005@qq.com";
        $actual = VerifyHelper::isEmail($data);
        self::assertTrue($actual);

        $data = "9727005.com@qq.com";
        $actual = VerifyHelper::isEmail($data);
        self::assertTrue($actual);

        $data = "9727005@qq";
        $actual = VerifyHelper::isEmail($data);
        self::assertFalse($actual);

        $data = "9727005#qq.com";
        $actual = VerifyHelper::isEmail($data);
        self::assertFalse($actual);
    }
}
