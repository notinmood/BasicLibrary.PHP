<?php
/**
 * @file   : ObjectHelperTest.php
 * @time   : 11:02
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

use Hiland\Test\res\Student;
use PHPUnit\Framework\TestCase;

class ObjectHelperTest extends TestCase
{

    public function testIsEmpty()
    {
        $actual = ObjectHelper::isEmpty("AAA");
        self::assertEquals(false, $actual);
    }

    public static function testGetClass()
    {
        $student = new Student("zhangsan", 20);

        $object = $student;
        $actual = ObjectHelper::getClassName($object);
        $expected = "Hiland\\Test\\res\\Student";
        self::assertEquals($expected, $actual);

        /**
         * 字符串不是class类型
         */
        $object = "qingdao";
        $actual = ObjectHelper::getClassName($object);
        self::assertNull($actual);

        /**
         * 数字不是class类型
         */
        $object = 123;
        $actual = ObjectHelper::getClassName($object);
        self::assertNull($actual);

        /**
         * array不是class类型
         */
        $object = array();
        $actual = ObjectHelper::getClassName($object);
        self::assertNull($actual);

        /**
         * 空对象是一个class类型
         */
        $object = new \stdClass();
        $actual = ObjectHelper::getClassName($object);
        self::assertEquals("stdClass", $actual);

        /**
         * 匿名函数是一个class类型,类型名称为 Closure
         */
        $object = function () {
        };
        $actual = ObjectHelper::getClassName($object);
        self::assertEquals("Closure", $actual);

        /**
         * 方法对应的类型是一个null
         */
        $object = $student->getUserName();
        $actual = ObjectHelper::getClassName($object);
        self::assertNull($actual);
    }

    public function testIsNumber()
    {
        $data = 12;
        $actual = ObjectHelper::isNumber($data);
        self::assertTrue($actual);

        $data = (float)12.45;
        $actual = ObjectHelper::isNumber($data);
        self::assertTrue($actual);

        $data = (double)12.45;
        $actual = ObjectHelper::isNumber($data);
        self::assertTrue($actual);

        $data = "12.45";
        $actual = ObjectHelper::isNumber($data);
        self::assertFalse($actual);
    }

    public function testToArray()
    {
        $s = new Student("zhangsan", 20);
        $actual =  ObjectHelper::toArray($s);
        $expected =  get_object_vars($s);
        // dump($actual);
        self::assertEquals($expected, $actual);
    }
}
