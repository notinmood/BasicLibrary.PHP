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
        $className = Student::class;
        $actual1 = InstanceHelper::get($className, "张三", 20);
        $this->assertInstanceOf($className, $actual1);


        $actual2 = InstanceHelper::get($className, "张三", 20);
        $this->assertEquals($actual2, $actual1);
        $this->assertSame($actual2, $actual1);


        $actualLisi = InstanceHelper::get($className, "lisi", 20);
        $this->assertNotEquals($actualLisi, $actual1);
        $this->assertNotSame($actualLisi, $actual1);


        $className = Teacher::class;
        $actualTeacher = InstanceHelper::get($className);
        $this->assertInstanceOf($className, $actualTeacher);
    }
}
