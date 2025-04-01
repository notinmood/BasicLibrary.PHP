<?php
/**
 * @file   : ZipTest.php
 * @time   : 13:48
 * @date   : 2025/4/1
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more. Simple is best!
 */

namespace Hiland\Test\pattern;

use PHPUnit\Framework\TestCase;
use function Hiland\Pattern\zip;

class ZipTest extends TestCase
{
    public function testZip(): void
    {
        $actual = zip(['a', 'b', 'c'], [1, 2, 3]);
        $expect = true;
        self::assertEquals($expect, $actual);
    }
}