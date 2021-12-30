<?php
/**
 * @file   : ReflectionHelperTest.php
 * @time   : 12:28
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

use Hiland\Test\_res\Student;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use function PHPUnit\Framework\assertEquals;

class ReflectionHelperTest extends TestCase
{
    // public function testGetReflectionClass()
    // {
    //
    // }

    /**
     * @throws ReflectionException
     */
    public function testExecuteInstanceMethod()
    {
        $student = new Student("zhangsan", 20);
        // echo $student->getUserName();
        $actual = ReflectionHelper::executeInstanceMethod(Student::class, "getUserName", $student);
        $expect = "zhangsan";
        assertEquals($expect, $actual);

        $methodArgs=["lisi"];
        ReflectionHelper::executeInstanceMethod(Student::class, "setUserName", $student,null,$methodArgs);
        $actual = ReflectionHelper::executeInstanceMethod(Student::class, "getUserName", $student);
        $expect = "lisi";
        assertEquals($expect, $actual);
    }

    public function testExecuteStaticMethod()
    {
        $actual= ReflectionHelper::executeStaticMethod(Student::class, "getTypeName");
        $expect= "这是一个学生";
        self::assertEquals($expect, $actual);

        $actual= ReflectionHelper::executeStaticMethod(Student::class, "getTypeNameEn","(USA)");
        $expect= "This is a Student (USA)";
        self::assertEquals($expect, $actual);
    }
}
