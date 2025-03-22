<?php
/**
 * @file   : ConvertorTest.php
 * @time   : 14:37
 * @date   : 2025/3/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\Test\data;

use Hiland\Data\Convertor;
use Hiland\Data\ObjectTypes;
use PHPUnit\Framework\TestCase;


class ConvertorTest extends TestCase
{
    public function testChangeType(): void
    {
        try {
            $actual = Convertor::changeType("123", ObjectTypes::INTEGER);
        } catch (\JsonException $e) {
            $actual = false;
        }
        $expect = 123;
        self::assertEquals($expect, $actual);


        try {
            $actual = Convertor::changeType("123.456", ObjectTypes::FLOAT);
        } catch (\JsonException $e) {
            $actual = false;
        }
        $expect = 123.456;
        self::assertEquals($expect, $actual);

        try {
            $actual = Convertor::changeType("123.456", ObjectTypes::INTEGER);
        } catch (\JsonException $e) {
            $actual = false;
        }
        $expect = 123;
        self::assertEquals($expect, $actual);
    }


}