<?php
/**
 * @file   : ConfigHelperTest.php
 * @time   : 21:40
 * @date   : 2021/9/5
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use Hiland\Config\ConfigClient;
use PHPUnit\Framework\TestCase;

// +--------------------------------------------------------------------------
// |::说明：| 由于本类型几个方法内使用的ConfigHelper 和 ConfigMate 会相互影响，本类型内的几个方法请分别单独测试。
// |·······| (具体查看_README.md文件)
// +--------------------------------------------------------------------------

class ConfigHelperTest extends TestCase
{
    public function testGet1(): void
    {
        $key    = "d.dA";
        $actual = ConfigClient::get($key);
        $expect = "dA-content";
        self::assertEquals($expect, $actual);

        $key    = "archive.host";
        $actual = ConfigClient::get($key, null, "demo.config.ini");
        $expect = "localhost";
        self::assertEquals($expect, $actual);

        /**
         * 测试上一步通过 get()第三个参数加载的配置文件
         */
        $key    = "archive.database";
        $actual = ConfigClient::get($key, null);
        $expect = "archive";
        self::assertEquals($expect, $actual);

        /**
         * 再次单独使用 get 方法的时候, 会使用默认的配置文件
         */
        $key    = "d.dA";
        $actual = ConfigClient::get($key);
        $expect = "dA-content";
        self::assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function testGet2(): void
    {
        ConfigClient::loadFile("demo.config.ini");
        self::assertEquals(1, 1);

        /**
         * 如果在第一次get前,明确使用 LoadFile时候，就不加载缺省的config.***文件了
         */
        $key    = "d.dA";
        $actual = ConfigClient::get($key);
        $expect = null;
        self::assertEquals($expect, $actual);

        $key    = "archive.database";
        $actual = ConfigClient::get($key);
        $expect = "archive";
        self::assertEquals($expect, $actual);
    }

    /**
     * 测试 .env 优先生效
     * @return void
     */
    public function testGet3(): void
    {
        $key    = "city";
        $actual = ConfigClient::get($key);
        $expect = "qingdao";
        self::assertEquals($expect, $actual);

        $key    = "base.host";
        $actual = ConfigClient::get($key);
        $expect = "env.localhost";
        self::assertEquals($expect, $actual);

        $key    = "www";
        $actual = ConfigClient::get($key, "");
        $expect = "";
        self::assertEquals($expect, $actual);

        $key    = "base.12w6ww";
        $actual = ConfigClient::get($key, "");
        $expect = "";
        self::assertEquals($expect, $actual);
    }

    public function testGet4(): void
    {
        $key    = "needServerValidateSign";
        $actual = ConfigClient::get($key);
        $expect = false;
        self::assertEquals($expect, $actual);
    }

    /**
     * TODO:这个方法单独执行没有问题，但整个文件一起执行，就报错。
     * @return void
     */
    public function testGetSection1(): void
    {
        $key    = "archive.host";
        $actual = ConfigClient::get($key, null, "demo.config.ini");
        $expect = "localhost";
        self::assertEquals($expect, $actual);

        $key    = "office";
        $actual = ConfigClient::get($key);
        $expect = null;
        self::assertEquals($expect, $actual);

        ConfigClient::loadFile("config.php");
        $key    = "office";
        $actual = ConfigClient::get($key);
        $expect = ["MS", "WPS"];
        self::assertEquals($expect, $actual);
    }

    public function testGetSection2(): void
    {
        $key    = "base";
        $actual = ConfigClient::get($key);
        $expect = ["host"     => "env.localhost",
                   "database" => "env.default",
                   "address"  => "env.address"];
        self::assertEquals($expect, $actual);
    }

    /**
     * 测试 .env 内的数据
     * @return void
     */
    public function testGetSection3(): void
    {
        $key    = "users";
        $actual = ConfigClient::get($key);
        $expect = ["host"     => "env.localhost",
                   "database" => "users",
                   "address"  => "env.address"];
        self::assertEquals($expect, $actual);
    }
}
