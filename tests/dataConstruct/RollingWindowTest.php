<?php
/**
 * @file   : RollingWindowTest.php
 * @time   : 17:14
 * @date   : 2025/3/10
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\Test\data;

use Hiland\DataConstruct\RollingWindow;
use PHPUnit\Framework\TestCase;

class RollingWindowTest extends TestCase
{
    public function testGetMovingAverage(): void
    {
        $arr    = [1, 2, 3, 4, 5];
        $rw     = new RollingWindow($arr);
        $actual = $rw->getMovingAverage(3);
        $expect = [NAN, NAN, 2, 3, 4];
        self::assertTrue(is_nan(($actual[0])));
        self::assertTrue(is_nan(($actual[1])));
        self::assertEquals($expect[2], $actual[2]);
        self::assertEquals($expect[3], $actual[3]);
        self::assertEquals($expect[4], $actual[4]);

        $rw->appendData([6]); //TODO:xiedali@2025/03/10 为什么不能传递多个独立的数字？
        $actual = $rw->getMovingAverage(3);
        $expect = [NAN, NAN, 2, 3, 4, 5];
        self::assertTrue(is_nan(($actual[0])));
        self::assertTrue(is_nan(($actual[1])));
        self::assertEquals($expect[2], $actual[2]);
        self::assertEquals($expect[3], $actual[3]);
        self::assertEquals($expect[4], $actual[4]);
        self::assertEquals($expect[5], $actual[5]);

    }


}
