<?php

namespace Hiland\Test\io;

use Hiland\IO\DirHelper;
use PHPUnit\Framework\TestCase;

class DirHelperTest extends TestCase
{
    protected $tempDir;

    protected function setUp(): void
    {
        // 创建一个临时目录来进行测试
        $this->tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DirHelperTest';
        if (is_dir($this->tempDir)) {
            DirHelper::removeDir($this->tempDir);
        }
        mkdir($this->tempDir, 0777, true);
    }

    protected function tearDown(): void
    {
        // 测试结束后删除临时目录
        if (is_dir($this->tempDir)) {
            DirHelper::removeDir($this->tempDir);
        }
    }

    // 测试确保目录存在功能，正常情况
    public function testEnsurePathExist_HappyPath()
    {
        $path = $this->tempDir . DIRECTORY_SEPARATOR . 'happyPath';
        $this->assertFalse(is_dir($path));
        DirHelper::ensurePathExist($path);
        $this->assertTrue(is_dir($path));
    }

    // 测试确保目录存在功能，当目录已经存在时
    public function testEnsurePathExist_AlreadyExists()
    {
        $path = $this->tempDir;
        $this->assertTrue(is_dir($path));
        DirHelper::ensurePathExist($path);
        $this->assertTrue(is_dir($path));
    }

    // 测试获取文件数目，正常情况
    public function testGetFileCount_HappyPath()
    {
        $filePath = $this->tempDir . DIRECTORY_SEPARATOR . 'testFile.txt';
        file_put_contents($filePath, 'test content');
        $this->assertEquals(1, DirHelper::getFileCount($this->tempDir));
    }

    // 测试获取文件数目，目录为空
    public function testGetFileCount_EmptyDir()
    {
        $this->assertEquals(0, DirHelper::getFileCount($this->tempDir));
    }

    // 测试获取文件数目，目录不存在
    public function testGetFileCount_DirNotExists()
    {
        $nonExistentDir = $this->tempDir . DIRECTORY_SEPARATOR . 'nonExistentDir';
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Directory ".+" was not created/');
        DirHelper::getFileCount($nonExistentDir);
    }

    // 测试确保路径以路径分隔符结尾，正常情况
    public function testEnsureEndWithPathSeparator_HappyPath()
    {
        $path = DirHelper::ensureEndWithPathSeparator($this->tempDir);
        $this->assertTrue(StringHelper::isEndWith($path, DIRECTORY_SEPARATOR));
    }

    // 测试确保路径以路径分隔符结尾，路径已经以路径分隔符结尾
    public function testEnsureEndWithPathSeparator_AlreadyEndsWithSeparator()
    {
        $path = DirHelper::ensureEndWithPathSeparator($this->tempDir . DIRECTORY_SEPARATOR);
        $this->assertTrue(StringHelper::isEndWith($path, DIRECTORY_SEPARATOR));
    }

    // 测试遍历目录功能，正常情况
    public function testWalk_HappyPath()
    {
        $filePath = $this->tempDir . DIRECTORY_SEPARATOR . 'testFile.txt';
        file_put_contents($filePath, 'test content');
        $foundFiles = [];
        DirHelper::walk($this->tempDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        });
        $this->assertCount(1, $foundFiles);
        $this->assertEquals($filePath, $foundFiles[0]);
    }

    // 测试遍历目录功能，目录为空
    public function testWalk_EmptyDir()
    {
        $foundFiles = [];
        DirHelper::walk($this->tempDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        });
        $this->assertCount(0, $foundFiles);
    }

    // 测试遍历目录功能，目录不存在
    public function testWalk_DirNotExists()
    {
        $nonExistentDir = $this->tempDir . DIRECTORY_SEPARATOR . 'nonExistentDir';
        $foundFiles = [];
        DirHelper::walk($nonExistentDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        });
        $this->assertCount(0, $foundFiles);
    }

    // 测试遍历目录功能，递归遍历子目录
    public function testWalk_Recursive()
    {
        $subDir = $this->tempDir . DIRECTORY_SEPARATOR . 'subDir';
        mkdir($subDir, 0777, true);
        $subFilePath = $subDir . DIRECTORY_SEPARATOR . 'subTestFile.txt';
        file_put_contents($subFilePath, 'test content');
        $foundFiles = [];
        DirHelper::walk($this->tempDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        }, true);
        $this->assertCount(1, $foundFiles);
        $this->assertEquals($subFilePath, $foundFiles[0]);
    }

    // 测试删除目录功能，正常情况
    public function testRemoveDir_HappyPath()
    {
        $subDir = $this->tempDir . DIRECTORY_SEPARATOR . 'subDir';
        mkdir($subDir, 0777, true);
        $filePath = $subDir . DIRECTORY_SEPARATOR . 'testFile.txt';
        file_put_contents($filePath, 'test content');
        $this->assertTrue(is_dir($subDir));
        $this->assertTrue(file_exists($filePath));
        DirHelper::removeDir($subDir);
        $this->assertFalse(is_dir($subDir));
        $this->assertFalse(file_exists($filePath));
    }

    // 测试删除目录功能，目录不存在
    public function testRemoveDir_DirNotExists()
    {
        $nonExistentDir = $this->tempDir . DIRECTORY_SEPARATOR . 'nonExistentDir';
        $this->assertFalse(is_dir($nonExistentDir));
        DirHelper::removeDir($nonExistentDir); // 预期不抛出异常
        $this->assertFalse(is_dir($nonExistentDir));
    }
}