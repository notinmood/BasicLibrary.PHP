<?php
/**
 * @file   : ObjectHelperTest.php
 * @time   : 11:02
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use Hiland\Test\_res\Student;
use Hiland\Data\ObjectHelper;
use Hiland\Data\ObjectTypes;
use PHPUnit\Framework\TestCase;
use stdClass;

class ObjectHelperTest extends TestCase
{
    public function testIsInstance()
    {
        $s        = new Student("zhangsan", 20);
        $actual   = ObjectHelper::isInstance($s, Student::class);
        $expected = true;
        self::assertEquals($expected, $actual);

        $s        = new stdClass();
        $actual   = ObjectHelper::isInstance($s, Student::class);
        $expected = false;
        self::assertEquals($expected, $actual);
    }

    public function testIsJson()
    {
        $data     = "123";
        $actual   = ObjectHelper::isJson($data);
        $expected = false;
        self::assertEquals($expected, $actual);

        $json     = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
        $actual   = ObjectHelper::isJson($json);
        $expected = true;
        self::assertEquals($expected, $actual);

        $json     = '{"a","b"}';
        $actual   = ObjectHelper::isJson($json);
        $expected = false;
        self::assertEquals($expected, $actual);
    }

    public function testIsEmpty()
    {
        $actual = ObjectHelper::isEmpty("AAA");
        self::assertEquals(false, $actual);
    }

    public static function testGetClassName()
    {
        $student = new Student("zhangsan", 20);

        $object   = $student;
        $actual   = ObjectHelper::getClassName($object);
        $expected = "Hiland\\Test\\_res\\Student";
        self::assertEquals($expected, $actual);

        /**
         * 字符串不是class类型
         */
        $object   = "qingdao";
        $actual   = ObjectHelper::getClassName($object);
        $expected = ObjectTypes::STRING;
        self::assertEquals($expected, $actual);

        /**
         * 数字不是class类型
         */
        $object   = 123;
        $actual   = ObjectHelper::getClassName($object);
        $expected = ObjectTypes::INTEGER;
        self::assertEquals($expected, $actual);

        /**
         * array不是class类型
         */
        $object   = array();
        $actual   = ObjectHelper::getClassName($object);
        $expected = ObjectTypes::ARRAYS;
        self::assertEquals($expected, $actual);

        /**
         * 空对象是一个class类型
         */
        $object = new stdClass();
        $actual = ObjectHelper::getClassName($object);
        self::assertEquals("stdClass", $actual);

        /**
         * 匿名函数是一个 class 类型,类型名称为 Closure
         */
        $object   = function () {

        };
        $actual   = ObjectHelper::getClassName($object);
        $expected = ObjectTypes::CLOSURE;
        self::assertEquals($expected, $actual);
    }

    public function testIsNumber()
    {
        $data   = 12;
        $actual = ObjectHelper::isNumber($data);
        self::assertTrue($actual);

        $data   = 12.45;
        $actual = ObjectHelper::isNumber($data);
        self::assertTrue($actual);

        $data   = 12.45;
        $actual = ObjectHelper::isNumber($data);
        self::assertTrue($actual);

        $data   = "12.45";
        $actual = ObjectHelper::isNumber($data);
        self::assertFalse($actual);

        /**
         * 用双引号括起来的数值，使用 is_numeric 判断的时候返回 true.
         */
        $data   = "12.45";
        $actual = is_numeric($data);
        self::assertTrue($actual);
    }

    public function testToArray()
    {
        $s        = new Student("zhangsan", 20);
        $actual   = ObjectHelper::convertTOArray($s);
        $expected = get_object_vars($s);
        // dump($actual);
        self::assertEquals($expected, $actual);
    }
}
