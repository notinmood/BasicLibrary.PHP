<?php
/**
 * @file: MathHelperTest.php
 * @time: 8:56
 * @date: 2021/8/8
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\Utils\Data;

use Hiland\Utils\Data\MathHelper;
use PHPUnit\Framework\TestCase;

class MathHelperTest extends TestCase
{

    public function testFloat2Percent()
    {
        $data = 2.78536;
        $r1 = MathHelper::convertFloatToPercent($data);
        $r2 = MathHelper::convertFloatToPercent($data, 2);
        $this->assertEquals($r2, $r1);
        $this->assertEquals("278.54%", $r1);

        $r3 = MathHelper::convertFloatToPercent($data, 1);
        $this->assertEquals("278.5%", $r3);
    }

    public function testPercent2Float()
    {
        $data = "278.54%";
        $r1 = MathHelper::convertPercentToFloat($data);
        $this->assertEquals(2.7854, $r1);
    }
    
    public function testConvertBase(){
        $actual = MathHelper::convertBase("10", 2, 10);
        $expected = 2;
        self::assertEquals($expected, $actual);

        $actual = MathHelper::convertBase("10", 10, 16);
        $expected = "a";
        self::assertEquals($expected, $actual);

        $actual = MathHelper::convertBase("10", 8, 2);
        $expected = "1000";
        self::assertEquals($expected, $actual);
    }

    public function testSma()
    {
        //测试一维数组
        $original1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected1 = [0, 0, 2, 3, 4, 5, 6, 7, 8, 9];
        $actual1 = MathHelper::sma($original1, 3);
        $this->assertEquals($expected1, $actual1);

        //测试二维数组
        $original2[] = ["age" => 11, "height" => 180];
        $original2[] = ["age" => 12, "height" => 179];
        $original2[] = ["age" => 13, "height" => 178];
        $original2[] = ["age" => 14, "height" => 177];
        $original2[] = ["age" => 15, "height" => 176];
        $original2[] = ["age" => 16, "height" => 175];
        $original2[] = ["age" => 17, "height" => 174];
        $original2[] = ["age" => 18, "height" => 173];
        $original2[] = ["age" => 19, "height" => 172];
        $original2[] = ["age" => 20, "height" => 171];

        $expected2_age = [0, 0, 12, 13, 14, 15, 16, 17, 18, 19];
        $actual2_age = MathHelper::sma($original2, 3, "age");
        $this->assertEquals($expected2_age, $actual2_age);

        $expected2_height = [0, 0, 179, 178, 177, 176, 175, 174, 173, 172];
        $actual2_height = MathHelper::sma($original2, 3, "height");
        $this->assertEquals($expected2_height, $actual2_height);
    }
}
