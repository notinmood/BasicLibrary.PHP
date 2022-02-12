<?php
/**
 * @file   : Base64HelperTest.php
 * @time   : 15:59
 * @date   : 2021/9/10
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use Hiland\Utils\Data\Base64Helper;
use PHPUnit\Framework\TestCase;

class Base64HelperTest extends TestCase
{
    public function testEncode(): void
    {
        $data     = "你好，我是山东解大劦 Shandong Xiedali";
        $actual   = Base64Helper::encode($data);
        $expected = "5L2g5aW977yM5oiR5piv5bGx5Lic6Kej5aSn5YqmIFNoYW5kb25nIFhpZWRhbGk=";
        self::assertEquals($expected, $actual);

        $data   = "PHP 删除数组中的元素菜鸟教程";
        $actual = Base64Helper::encode($data);
        /** @noinspection all */
        $expected = "UEhQIOWIoOmZpOaVsOe7hOS4reeahOWFg+e0oOiPnOm4n+aVmeeoiw==";
        self::assertEquals($expected, $actual);

        $data   = "PHP 删除数组中的元素菜鸟教程";
        $actual = Base64Helper::encode($data, true);
        /** @noinspection all */
        $expected = "UEhQIOWIoOmZpOaVsOe7hOS4reeahOWFg_e0oOiPnOm4n_aVmeeoiw--";
        self::assertEquals($expected, $actual);
    }

    public function testDecode(): void
    {
        $data     = "5L2g5aW977yM5oiR5piv5bGx5Lic6Kej5aSn5YqmIFNoYW5kb25nIFhpZWRhbGk=";
        $actual   = Base64Helper::decode($data);
        $expected = "你好，我是山东解大劦 Shandong Xiedali";
        self::assertEquals($expected, $actual);
        /** @noinspection all */
        $data     = "UEhQIOWIoOmZpOaVsOe7hOS4reeahOWFg_e0oOiPnOm4n_aVmeeoiw--";
        $actual   = Base64Helper::decode($data);
        $expected = "PHP 删除数组中的元素菜鸟教程";
        self::assertEquals($expected, $actual);
    }

    public function testIsBase64(): void
    {
        $data   = "5L2g5aW977yM5oiR5piv5bGx5Lic6Kej5aSn5YqmIFNoYW5kb25nIFhpZWRhbGk=";
        $actual = Base64Helper::isBase64($data);
        self::assertTrue($actual);

        $data   = "5L2g5aW977sww4f.";
        $actual = Base64Helper::isBase64($data);
        self::assertFalse($actual);
    }
}
