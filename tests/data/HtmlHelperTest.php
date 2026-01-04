<?php
/**
 * @file   : HtmlHelperTest.php
 * @time   : 14:46
 * @date   : 2021/9/16
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Hiland\Data\HtmlHelper;
use PHPUnit\Framework\TestCase;

class HtmlHelperTest extends TestCase
{
    public function testCleanComment(): void
    {
        $myData = <<<myData
<body><!--topTools Start --><a href="/" target="_blank">脚本之家</a>";
myData;

        $expected = <<<expected
<body><a href="/" target="_blank">脚本之家</a>";
expected;

        $actual = HtmlHelper::cleanComment($myData);
        self::assertEquals($expected, $actual);
    }
}
