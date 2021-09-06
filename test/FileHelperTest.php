<?php
/**
 * @file   : FileHelperTest.php
 * @time   : 8:58
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\IO;

use PHPUnit\Framework\TestCase;

class FileHelperTest extends TestCase
{
    public function testGetFileEncoding()
    {
        $fileName = PathHelper::combine(__DIR__, "res/utf-8.txt");
        $actual = FileHelper::getFileEncoding($fileName);
        $this->assertEquals("UTF-8", $actual);

        $fileName = PathHelper::combine(__DIR__,"res/gb2312.txt");
        $actual = FileHelper::getFileEncoding($fileName);
        $this->assertEquals("EUC-CN", $actual);
    }


    public function testGetFileExtensionName()
    {
        $fileName= "E:\\myworkspace\\MyStudy\\WebPackStudy\\index.js";
        $actual= FileHelper::getFileExtensionName($fileName);
        $expected= "js";
        self::assertEquals($expected, $actual);

        $fileName= "MyStudy\\WebPackStudy\\index.js";
        $actual= FileHelper::getFileExtensionName($fileName);
        $expected= "js";
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
