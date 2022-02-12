<?php
/**
 * @file   : InstanceContainerTest.php
 * @time   : 20:15
 * @date   : 2021/9/21
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Pattern;

use Hiland\Test\_res\Student;
use Hiland\Test\_res\Teacher;
use PHPUnit\Framework\TestCase;

class InstanceContainerTest extends TestCase
{
    public function testGet()
    {
        /**
         * 构造函数带参数的sample
         */
        $className = Student::class;
        $actual1 = InstanceContainer::get($className, "张三", 20);
        $this->assertInstanceOf($className, $actual1);


        $actual2 = InstanceContainer::get($className, "张三", 20);
        $this->assertEquals($actual2, $actual1);
        $this->assertSame($actual2, $actual1);


        $actualLisi = InstanceContainer::get($className, "lisi", 20);
        $this->assertNotEquals($actualLisi, $actual1);
        $this->assertNotSame($actualLisi, $actual1);

        $actual = InstanceContainer::has($className, "张三", 20);
        self::assertTrue($actual);

        $actual = InstanceContainer::has($className, "张三", 21);
        self::assertFalse($actual);

        /**
         * 构造函数不带参数的sample
         */
        $className = Teacher::class;
        $actualTeacher = InstanceContainer::get($className);
        $this->assertInstanceOf($className, $actualTeacher);

        /**
         * 本段代码演示从容器中取出的对象实例,也可以进行代码提示.
         */
        if ($actualTeacher instanceof Teacher) {
            echo $actualTeacher->school;
        }
    }

    // public function testReflectClass()
    // {
    //     $typeName = Teacher::class;
    //     $ref = new ReflectionClass($typeName);
    //     dump($ref);
    //
    //     $className = Teacher::class;
    //     $actualTeacher = InstanceContainer::get($className);
    //
    //
    //     $actualTeacher-> school;
    //     // $p = new TypeResolver();
    //     // $p = $p->resolve($typeName);
    //     $p ="1";
    //     settype($actualTeacher, $typeName);
    //     // $actualTeacher->
    //
    //     dump($p);
    // }
}
