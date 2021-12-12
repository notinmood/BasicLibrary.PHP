<?php
/**
 * @file   : ChineseHelperTest.php
 * @time   : 9:25
 * @date   : 2021/11/7
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

use PHPUnit\Framework\TestCase;

class ChineseHelperTest extends TestCase
{
    public function testGetPinyin(){
        $data= "解放军";
        $expected = "jiefangjun";
        $actual = ChineseHelper::getPinyin($data);
        self::assertEquals($expected,$actual);

        $data= "中国人";
        $expected = "zhongguoren";
        $actual = ChineseHelper::getPinyin($data);
        self::assertEquals($expected,$actual);
    }

    public function testGetFirstChar(){
        $data = "中国人";
        $expected = "Z";
        $actual = ChineseHelper::getFirstChar($data);
        self::assertEquals($expected,$actual);
    }
}