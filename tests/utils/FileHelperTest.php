<?php
/**
 * @file   : FileHelperTest.php
 * @time   : 8:58
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use Hiland\IO\FileHelper;
use Hiland\IO\PathHelper;
use PHPUnit\Framework\TestCase;

class FileHelperTest extends TestCase
{
    public function testGetFileEncoding(): void
    {
        $fileName = PathHelper::combine(__DIR__, "../_res/utf-8.txt");
        $actual   = FileHelper::getEncoding($fileName);
        $this->assertEquals("UTF-8", $actual);

        $fileName = PathHelper::combine(__DIR__, "../_res/gb2312.txt");
        $actual   = FileHelper::getEncoding($fileName);
        $this->assertEquals("EUC-CN", $actual);
    }


    public function testGetFileExtensionName(): void
    {
        $fileName = "E:\\myworkspace\\MyStudy\\WebPackStudy\\index.js";
        $actual   = FileHelper::getExtensionName($fileName);
        $expected = "js";
        self::assertEquals($expected, $actual);

        $fileName = "MyStudy\\WebPackStudy\\index.js";
        $actual   = FileHelper::getExtensionName($fileName);
        $expected = "js";
        self::assertEquals($expected, $actual);
    }

    // public function testGetEncodingContent()
    // {
    //
    // }
    //
    // public function testGetUploadedFileInfo()
    // {
    //
    // }
    //
    // public function testGetFileBaseNameWithoutExtension()
    // {
    //
    // }
    //
    // public function testGetFileBaseName()
    // {
    //
    // }
    //
    // public function testGetFileDirName()
    // {
    //
    // }
}
