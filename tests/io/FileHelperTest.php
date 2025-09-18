<?php
/**
 * @file   : FileHelperTest.php
 * @time   : 8:58
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\io;

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

        $fileName = "MyStudy\\WebPackStudy\\index.mjs";
        $actual   = FileHelper::getExtensionName($fileName);
        $expected = "mjs";
        self::assertEquals($expected, $actual);
    }

    public function testDealWithLineByLine(): void
    {
        // 创建一个临时的测试文件
        $testFile = tempnam(sys_get_temp_dir(), 'test_file');
        file_put_contents($testFile, "第一行\n第二行\n第三行");

        // 创建一个闭包函数来处理每一行
        $result       = [];
        $dealLineFunc = static function ($line) use (&$result) {
            $result[] = trim($line);
        };

        // 调用 dealWithLineByLine 方法
        FileHelper::dealWithLineByLine($testFile, $dealLineFunc);

        // 验证结果
        $expectedResult = [
            '第一行',
            '第二行',
            '第三行'
        ];
        $this->assertEquals($expectedResult, $result);

        // 删除临时文件
        unlink($testFile);
    }

    /**
     * 测试获取文件所在目录信息的正常情况
     */
    public function testGetDirNameHappyPath(): void
    {
        $fileFullName = '/path/to/file.txt';
        $this->assertEquals('/path/to', FileHelper::getDirName($fileFullName));
    }

    /**
     * 测试获取文件基本名称信息的正常情况
     */
    public function testGetBaseNameHappyPath(): void
    {
        $fileFullName = '/path/to/file.txt';
        $this->assertEquals('file.txt', FileHelper::getBaseName($fileFullName));
    }

    /**
     * 测试获取文件基本名称信息（不带扩展名）的正常情况
     */
    public function testGetBaseNameWithoutExtensionHappyPath(): void
    {
        $fileFullName = '/path/to/file.txt';
        $this->assertEquals('file', FileHelper::getBaseNameWithoutExtension($fileFullName));
    }

    /**
     * 测试获取文件扩展名的正常情况
     */
    public function testGetExtensionNameHappyPath(): void
    {
        $fileFullName = '/path/to/file.txt';
        $this->assertEquals('txt', FileHelper::getExtensionName($fileFullName));
    }

    /**
     * 测试获取上传文件信息的正常情况
     */
    public function testGetUploadedFileInfoHappyPath(): void
    {
        // 模拟上传文件信息
        $_FILES['file'] = [
            'name' => 'file.txt',
            'type' => 'text/plain',
            'tmp_name' => '/tmp/php123456',
            'error' => 0,
            'size' => 123
        ];

        $result = FileHelper::getUploadedFileInfo('file');
        $this->assertIsArray($result);
        $this->assertEquals('/tmp/php123456', $result['fullName']);
        $this->assertEquals('file.txt', $result['name']);
        $this->assertEquals('text/plain', $result['type']);
        $this->assertEquals(123, $result['size']);
    }

    /**
     * 测试获取上传文件信息时文件错误的情况
     */
    public function testGetUploadedFileInfoWithError(): void
    {
        // 模拟上传文件信息错误
        $_FILES['file'] = [
            'error' => 1
        ];

        $result = FileHelper::getUploadedFileInfo('file');
        $this->assertFalse($result);
    }

    /**
     * 测试获取文件编码信息的正常情况
     */
    public function testGetEncodingHappyPath(): void
    {
        $fileFullName = __DIR__ . '/testfile_utf8.txt';
        file_put_contents($fileFullName, '测试文件内容');

        $encoding = FileHelper::getEncoding($fileFullName);
        $this->assertEquals('UTF-8', $encoding);
    }

    /**
     * 测试获取文件内容（不指定目标编码）的正常情况
     */
    public function testGetContentWithoutTargetEncodingHappyPath(): void
    {
        $fileFullName = __DIR__ . '/testfile_utf8.txt';
        file_put_contents($fileFullName, '测试文件内容');

        $content = FileHelper::getContent($fileFullName);
        $this->assertEquals('测试文件内容', $content);
    }

    /**
     * 测试获取文件内容（指定目标编码）的正常情况
     */
    public function testGetContentWithTargetEncodingHappyPath(): void
    {
        $fileFullName = __DIR__ . '/testfile_utf8.txt';
        file_put_contents($fileFullName, '测试文件内容');

        $content = FileHelper::getContent($fileFullName, 'GB2312');
        $this->assertEquals(mb_convert_encoding('测试文件内容', 'GB2312', 'UTF-8'), $content);
    }

    /**
     * 测试逐行处理文件内容的正常情况
     */
    public function testDealWithLineByLineHappyPath(): void
    {
        $fileFullName = __DIR__ . '/testfile_lines.txt';
        $lines        = "第一行\n第二行\n第三行";
        file_put_contents($fileFullName, $lines);

        $result = [];
        FileHelper::dealWithLineByLine($fileFullName, static function ($line) use (&$result) {
            $result[] = trim($line);
        });

        $this->assertEquals(['第一行', '第二行', '第三行'], $result);
    }

    /**
     * 测试获取文件所在目录信息时文件名为空的情况
     */
    public function testGetDirNameWithEmptyFileName(): void
    {
        $fileFullName = '';
        $this->assertEquals('', FileHelper::getDirName($fileFullName));
    }

    /**
     * 测试获取文件基本名称信息（不带路径）时文件名为空的情况
     */
    public function testGetBaseNameWithEmptyFileName(): void
    {
        $fileFullName = '';
        $this->assertEquals('', FileHelper::getBaseName($fileFullName));
    }

    /**
     * 测试获取文件基本名称信息（不带扩展名）时文件名为空的情况
     */
    public function testGetBaseNameWithoutExtensionWithEmptyFileName(): void
    {
        $fileFullName = '';
        $this->assertEquals('', FileHelper::getBaseNameWithoutExtension($fileFullName));
    }

    /**
     * 测试获取文件扩展名时文件名为空的情况
     */
    public function testGetExtensionNameWithEmptyFileName(): void
    {
        $fileFullName = '';
        $this->assertEquals('', FileHelper::getExtensionName($fileFullName));
    }

    /**
     * 测试获取文件编码信息时文件不存在的情况
     */
    public function testGetEncodingWithNonexistentFile(): void
    {
        $fileFullName = __DIR__ . '/nonexistent.txt';
        $encoding     = FileHelper::getEncoding($fileFullName);
        $this->assertFalse($encoding);
    }

    /**
     * 测试获取文件内容时文件不存在的情况
     */
    public function testGetContentWithNonexistentFile(): void
    {
        $fileFullName = __DIR__ . '/nonexistent.txt';
        $content      = FileHelper::getContent($fileFullName);
        $this->assertFalse($content);
    }

    /**
     * 测试逐行处理文件内容时文件不存在的情况
     */
    public function testDealWithLineByLineWithNonexistentFile(): void
    {
        $fileFullName = __DIR__ . '/nonexistent.txt';
        $result       = [];
        FileHelper::dealWithLineByLine($fileFullName, static function ($line) use (&$result) {
            $result[] = trim($line);
        });
        $this->assertEquals([], $result);
    }

    protected function tearDown(): void
    {
        // 清理测试文件
        $files = [
            __DIR__ . '/testfile_utf8.txt',
            __DIR__ . '/testfile_lines.txt',
            __DIR__ . '/nonexistent.txt',
        ];

        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
