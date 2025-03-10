<?php
/**
 * @file   : NullObjectTest.php
 * @time   : 10:35
 * @date   : 2021/10/12
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Exception;
use Hiland\Test\_res\Student;
use Hiland\Pattern\NullObject;
use PHPUnit\Framework\TestCase;

class NullObjectTest extends TestCase
{
    public function getStudent($name, $age, $nullObjectAction = NullObject::NON)
    {
        if ($age < 25) {
            return new Student($name, $age);
        } else {
            return new NullObject($nullObjectAction);
        }
    }

    public function testObject()
    {
        $age      = 20;
        $name     = "张三";
        $student  = $this->getStudent($name, $age);
        $actual   = $student->getUserName();
        $expected = $name;
        self::assertEquals($expected, $actual);

        $age      = 28;
        $name     = "李四";
        $student  = $this->getStudent($name, $age);
        $actual   = $student->getUserName();
        $expected = null;
        self::assertEquals($expected, $actual);

        $student  = $this->getStudent($name, $age, NullObject::TIP);
        $actual   = $student->getUserName();
        $expected = null;
        self::assertEquals($expected, $actual);
        self::expectOutputString("当前为一个空对象,在其上调用方法 getUserName 没有任何效果");

        /**
         * 以下演示断言异常的方法
         */
        $this->expectException(Exception::class);
        // $this->expectExceptionMessage("当前为一个空对象,在其上调用方法getUserName没有任何效果");
        $student = $this->getStudent($name, $age, NullObject::EXCEPTION);
        $userName= $student->getUserName();
        dump($userName);
    }
}
