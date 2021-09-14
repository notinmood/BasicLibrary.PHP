<?php
/**
 * @file: StringHelperTest.php
 * @time: 12:03
 * @date: 2021/8/7
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\Utils\Data;

use Hiland\Utils\Data\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testIsEndWith()
    {
        $whole= "吴军信息传";
        $padding= "信息";
        $result= StringHelper::isEndWith($whole, $padding);
        $this->assertFalse($result);

        $padding= "息传";
        $result= StringHelper::isEndWith($whole, $padding);
        $this->assertTrue($result);
    }

    public function testIsStartWith()
    {
        $whole= "吴军信息传";
        $padding= "1吴军";
        $result= StringHelper::isStartWith($whole, $padding);
        $this->assertFalse($result);

        $padding= "吴军";
        $result= StringHelper::isStartWith($whole, $padding);
        $this->assertTrue($result);
    }

    public function testGrouping(){
        $data= "13573290346";
        $formator= "{3}-{4}-{4}";
        $actual = StringHelper::grouping($data, $formator);
        $expected= "135-7329-0346";
        self::assertEquals($expected, $actual);
    }
}
