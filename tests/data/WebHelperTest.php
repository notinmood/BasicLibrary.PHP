<?php
/**
 * @file   : WebHelperTest.php
 * @time   : 10:53
 * @date   : 2021/9/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Hiland\Web\WebHelper;
use PHPUnit\Framework\TestCase;

class WebHelperTest extends TestCase
{
    public function testGetWebMetas(): void
    {
        $url    = 'https://www.jb51.net';
        $result = WebHelper::getWebMetas($url);
        dump($result);

        $result = WebHelper::getWebMetas($url, "keyword");
        dump($result);

        self::assertTrue(true);
    }


    public function testConvertArrayToUrlParameter(): void
    {
        $array  = array(
            "name" => "shandong",
            "age" => 25,
            "city" => "shanghai"
        );

        $actual = WebHelper::convertArrayToUrlParameter($array);
        $expected = "age=25&city=shanghai&name=shandong";
        self::assertEquals($expected,$actual);


        $excludeKeys = array("name", "age");
        $actual = WebHelper::convertArrayToUrlParameter($array,false,$excludeKeys);
        $expected = "city=shanghai";
        self::assertEquals($expected,$actual);
    }

}
