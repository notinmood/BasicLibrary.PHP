<?php
/**
 * @file   : PathHelperTest.php
 * @time   : 11:48
 * @date   : 2021/9/5
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\IO;

use PHPUnit\Framework\TestCase;

class PathHelperTest extends TestCase
{

    public function testCombine()
    {
        $p1= "aa\\";
        $p2= "bb/";
        $p3= "cc";
        $p4= "dd.php";

        $real= PathHelper::combine($p1,$p2,$p3,$p4);
        $desire= "aa\\bb\\cc\\dd.php";
        self::assertEquals($desire, $real);
    }
}
