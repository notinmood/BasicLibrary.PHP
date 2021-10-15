<?php
/**
 * @file   : WebHelperTest.php
 * @time   : 10:53
 * @date   : 2021/9/22
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Web;

use PHPUnit\Framework\TestCase;

class WebHelperTest extends TestCase
{
    public function testGetWebMetas(){
        $url= 'https://www.jb51.net';
        $result= WebHelper::getWebMetas($url);
        dump($result);
        
        $result = WebHelper::getWebMetas($url,"keyword");
        dump($result);
        
        self::assertEquals(true, true);
    }

    // public function testJsonp()
    // {
    //
    // }
    //
    // public function testGetWebApp()
    // {
    //
    // }
    //
    // public function testServerReturn()
    // {
    //
    // }
}
