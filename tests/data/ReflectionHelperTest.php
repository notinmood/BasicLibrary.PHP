<?php
/**
 * @file   : ReflectionHelperTest.php
 * @time   : 12:28
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Hiland\Test\_res\Student;
use Hiland\Data\ReflectionHelper;
use PHPUnit\Framework\TestCase;

class ReflectionHelperTest extends TestCase
{
    /**
     * executeFunction 执行静态方法
     * @return void
     */
    public function testExecuteFunction1(): void
    {
        /**
         * 不带参数的静态方法
         */
        $className    = Student::class;
        $funcName     = "getTypeName";
        $funcFullName = [$className, $funcName];
        $actual       = ReflectionHelper::executeFunction($funcFullName);
        $expected     = "这是一个学生";
        self::assertEquals($expected, $actual);

        /**
         * 带参数的静态方法
         */
        $className    = Student::class;
        $funcName     = "display";
        $funcFullName = [$className, $funcName];
        $actual       = ReflectionHelper::executeFunction($funcFullName, "UK", "QD", "ShanDong");
        $expected     = "传递的参数为:UK/QD/ShanDong";
        self::assertEquals($expected, $actual);
    }

    /**
     * executeFunction 执行实例方法
     * @return void
     */
    public function testExecuteFunction2(): void
    {
        /**
         * 不带参数的实例方法
         */
        $instance     = new Student("zhangsan", 20);
        $funcName     = "getUserName";
        $funcFullName = [$instance, $funcName];
        $actual       = ReflectionHelper::executeFunction($funcFullName);
        $expected     = "zhangsan";
        self::assertEquals($expected, $actual);

        /**
         * 带参数的实例方法
         */
        $instance     = new Student("zhangsan", 20);
        $funcName     = "changeData";
        $funcFullName = [$instance, $funcName];
        $actual       = ReflectionHelper::executeFunction($funcFullName, "lisi", 21);
        $expected     = "name:lisi,age:21";
        self::assertEquals($expected, $actual);
    }

    /**
     * executeFunction 执行带命名空间的函数
     * @return void
     */
    public function testExecuteFunction3(): void
    {
        /**
         * 函数 helloBar 是定义在 Common/functions.php 内一个段逻辑，本函数仅仅用于单元测试
         */
        $funcFullName = "helloBar";
        $actual       = ReflectionHelper::executeFunction($funcFullName, "zhangsan");
        $expected     = "hello zhangsan";
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testExecuteInstanceMethod(): void
    {
        $student = new Student("zhangsan", 20);
        $actual  = ReflectionHelper::executeInstanceMethod(Student::class, "getUserName", $student);
        $expect  = "zhangsan";
        self::assertEquals($expect, $actual);

        $methodArgs = ["lisi"];
        ReflectionHelper::executeInstanceMethod(Student::class, "setUserName", $student, null, $methodArgs);
        $actual = ReflectionHelper::executeInstanceMethod(Student::class, "getUserName", $student);
        $expect = "lisi";
        self::assertEquals($expect, $actual);
    }

    public function testExecuteStaticMethod(): void
    {
        $actual = ReflectionHelper::executeStaticMethod(Student::class, "getTypeName");
        $expect = "这是一个学生";
        self::assertEquals($expect, $actual);

        $actual = ReflectionHelper::executeStaticMethod(Student::class, "getTypeNameEn", "(USA)");
        $expect = "This is a Student (USA)";
        self::assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function testGetInstanceProperty(): void
    {
        $student  = new Student("zhangsan", 20);
        $actual   = ReflectionHelper::getInstanceProperty(Student::class, "userName", $student);
        $expected = "zhangsan";
        self::assertEquals($expected, $actual);

        $actual   = ReflectionHelper::getInstanceProperty(Student::class, "userName", null, ["zhangsan", 20]);
        $expected = "zhangsan";
        self::assertEquals($expected, $actual);
    }
}
