<?php
/**
 * @file   : InstanceHelperTest.php
 * @time   : 20:15
 * @date   : 2021/9/21
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Pattern;

use Hiland\Test\res\Student;
use Hiland\Test\res\Teacher;
use PHPUnit\Framework\TestCase;

class InstanceHelperTest extends TestCase
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
}
