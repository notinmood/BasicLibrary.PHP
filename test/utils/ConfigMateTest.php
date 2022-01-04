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
        $actual = ConfigMate::Instance()->loadFile("config_test.php")->get('a');
        $expected = "T.AAA";
        self::assertEquals("$expected", "$actual");

        /**
         * 如果加载的多个配置文件内，都含有某个配置项，那么从第一个配置文件内读取
         * ────────────────────────
         * 本例中，demo.config.php的配置项a的值为 AAA,但因为 config_test.php 在前面已经加载了，
         * config_test.php内含有配置项a,其值为 T.AAA
         */
        $actual = ConfigMate::Instance()->loadFile("demo.config.php")->get('a');
        $expected = "T.AAA";
        self::assertEquals("$expected", "$actual");


        $actual = ConfigMate::Instance()->get('a');
        $expected = "T.AAA";
        self::assertEquals("$expected", "$actual");


        $actual = ConfigMate::Instance()->loadFile("demo.config.php")->get('d.dB.dBA');
        $expected = "T.dba-content";
        self::assertEquals("$expected", "$actual");

        //加载不存在的配置文件,也不会抛出异常
        $actual = ConfigMate::Instance()->loadFile("config22.php")->get('a');
        $expected = "T.AAA";
        self::assertEquals($expected,$actual);
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
