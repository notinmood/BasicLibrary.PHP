<?php
/**
 * @file   : ThreadHelperTest.php
 * @time   : 16:24
 * @date   : 2021/9/15
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use PHPUnit\Framework\TestCase;

class ThreadHelperTest extends TestCase
{
    public function testMultiRun()
    {
        // /**
        //  * 以下两条路径需要配置为真正的路径;
        //  * server.php内的逻辑sleep3秒.
        //  * 因为是两条并行,请手工验证以下代码,总时间是否为3秒左右
        //  */
        // $url1 = "http://localhost/PHP.Study/ThreadsStudy/S01MultiThreads/server.php";
        // $url2 = "http://localhost/PHP.Study/ThreadsStudy/S01MultiThreads/server.php";
        // echo date("Y-m-d H:i:s") . '-----' . time() . PHP_EOL;
        // ThreadHelper::multiRun($url1, $url2);
        // echo date("Y-m-d H:i:s") . '-----' . time() . PHP_EOL;
        //
        // $this->assertEmpty("");
        self::assertEquals(0, 0);
    }

    /**
     * 需要测试人员自己去目标文件server.php内查看是否被执行了(比如是否写了日志等)
     */
    public function testAsyncRun()
    {
        // $data = array('name' => 'guoyu', 'pwd' => '123456');
        // $url = 'http://localhost/PHP.Study/ThreadsStudy/S02AsyncThread/server.php';
        //
        // ThreadHelper::asyncRun($url, $data);
        // echo 'A.php success';
        //
        // $this->assertEmpty("");
        self::assertEquals(0, 0);
    }
}
