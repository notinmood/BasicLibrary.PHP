<?php
/**
 * @file   : ConfigMateTest.php
 * @time   : 11:29
 * @date   : 2021/9/5
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

use Hiland\Utils\Config\ConfigMate;
use Hiland\Utils\Data\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

class ConfigMateTest extends TestCase
{
    public function testGetParser()
    {
        //1、方法1
        $method = new ReflectionMethod(ConfigMate::class, "getParser");
        $method->setAccessible(TRUE);
        try {
            $parser = $method->invoke(null, "demo.config.json");
        } catch (ReflectionException $e) {
        }
        $parser = $parser->toString();
        self::assertEquals(@"Hiland\Utils\Config\ConfigParserJson", $parser);

        //2、方法2是对方法1的包装(方法查找、可视化设置、方法调用都进行了封装),
        //建议以后对私有成员的测试都采用此种方法
        $parser = ReflectionHelper::executeStaticMethod(ConfigMate::class, "getParser", "demo.config.php");
        $parser = $parser->toString();
        self::assertEquals(@"Hiland\Utils\Config\ConfigParserArray", $parser);
    }

    public function testArrayGet()
    {
        $actual = ConfigMate::Instance()->loadFile("demo.config.php")->get('a');
        $expected = "AAA";
        self::assertEquals("$expected", "$actual");

        $actual = ConfigMate::Instance()->loadFile("config_test.php")->get('a');
        $expected = "T.AAA";
        self::assertEquals("$expected", "$actual");

        //在没有指定新的config.php文件前,使用最近一次加载的config.php配置文件
        $actual = ConfigMate::Instance()->get('a');
        $expected = "T.AAA";
        self::assertEquals("$expected", "$actual");

        $actual = ConfigMate::Instance()->loadFile("demo.config.php")->get('a');
        $expected = "AAA";
        self::assertEquals("$expected", "$actual");

        $actual = ConfigMate::Instance()->loadFile("demo.config.php")->get('d.dB.dBA');
        $expected = "dba-content";
        self::assertEquals("$expected", "$actual");

        //判断不存在的配置文件
        $actual = ConfigMate::Instance()->loadFile("config22.php")->get('a');
        self::assertNull($actual);
    }

    public function testIniGet()
    {
        $actual = ConfigMate::Instance()->loadFile("demo.config.ini")->get('ga.non_section_node');
        // self::assertNull("$actual");

        $actual = ConfigMate::Instance()->get('base.host');
        $expected = "localhost";
        self::assertEquals("$expected", "$actual");

        $actual = ConfigMate::Instance()->get('base.database');
        $expected = "default";
        self::assertEquals("$expected", "$actual");

        $actual = ConfigMate::Instance()->get('archive.database');
        $expected = "archive";
        self::assertEquals("$expected", "$actual");
    }

    public function testJsonGet()
    {
        $actual = ConfigMate::Instance()->loadFile("demo.config.json")->get('ga.non_section_node');
        // self::assertNull("$actual");

        // $tt= ConfigMate::Instance()->getCurrentConfigContent();
        // var_dump($tt);

        $actual = ConfigMate::Instance()->get('1.0');
        $expected = "a";
        self::assertEquals("$expected", "$actual");

        $actual = ConfigMate::Instance()->get('2');
        $expected = "qingdao";
        self::assertEquals("$expected", "$actual");

        $actual = ConfigMate::Instance()->get('3.0');
        $expected = 1;
        self::assertEquals("$expected", "$actual");
    }
}
