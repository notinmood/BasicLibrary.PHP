<?php

namespace Hiland\Test\io;

use Hiland\Data\StringHelper;
use Hiland\IO\DirHelper;
use PHPUnit\Framework\TestCase;

class DirHelperTest extends TestCase
{
    protected string $tempDir;

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
    public function testEnsurePathExist_HappyPath(): void
    {
        $path = $this->tempDir . DIRECTORY_SEPARATOR . 'happyPath';
        $this->assertDirectoryDoesNotExist($path);
        DirHelper::ensurePathExist($path);
        $this->assertDirectoryExists($path);
    }

    // 测试确保目录存在功能，当目录已经存在时
    public function testEnsurePathExist_AlreadyExists(): void
    {
        $path = $this->tempDir;
        $this->assertDirectoryExists($path);
        DirHelper::ensurePathExist($path);
        $this->assertDirectoryExists($path);
    }

    // 测试获取文件数目，正常情况
    public function testGetFileCount_HappyPath(): void
    {
        $filePath = $this->tempDir . DIRECTORY_SEPARATOR . 'testFile.txt';
        file_put_contents($filePath, 'test content');
        $this->assertEquals(1, DirHelper::getFileCount($this->tempDir));
    }

    // 测试获取文件数目，目录为空
    public function testGetFileCount_EmptyDir(): void
    {
        $this->assertEquals(0, DirHelper::getFileCount($this->tempDir));
    }

    // 测试获取文件数目，目录不存在
    public function testGetFileCount_DirNotExists(): void
    {
        $nonExistentDir = $this->tempDir . DIRECTORY_SEPARATOR . 'nonExistentDir';
        $fileCount = DirHelper::getFileCount($nonExistentDir);
        $this->assertEquals(0, $fileCount);
    }

    // 测试确保路径以路径分隔符结尾，正常情况
    public function testEnsureEndWithPathSeparator_HappyPath(): void
    {
        $path = DirHelper::ensureEndWithPathSeparator($this->tempDir);
        $this->assertTrue(StringHelper::isEndWith($path, DIRECTORY_SEPARATOR));
    }

    // 测试确保路径以路径分隔符结尾，路径已经以路径分隔符结尾
    public function testEnsureEndWithPathSeparator_AlreadyEndsWithSeparator(): void
    {
        $path = DirHelper::ensureEndWithPathSeparator($this->tempDir . DIRECTORY_SEPARATOR);
        $this->assertTrue(StringHelper::isEndWith($path, DIRECTORY_SEPARATOR));
    }

    // 测试遍历目录功能，正常情况
    public function testWalk_HappyPath(): void
    {
        $subDir = __DIR__ . DIRECTORY_SEPARATOR . 'subDir';
        if (is_dir($subDir)) {
            DirHelper::removeDir($subDir);
        }
        mkdir($subDir, 0777, true);

        $filePath = $subDir. DIRECTORY_SEPARATOR . 'testFile.txt';
        file_put_contents($filePath, 'test content');

        $foundFiles = [];
        DirHelper::walk($subDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        });
        $this->assertCount(1, $foundFiles);
        $this->assertEquals($filePath, $foundFiles[0]);
    }

    // 测试遍历目录功能，目录为空
    public function testWalk_EmptyDir(): void
    {
        $foundFiles = [];
        DirHelper::walk($this->tempDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        });
        $this->assertCount(0, $foundFiles);
    }

    // 测试遍历目录功能，目录不存在
    public function testWalk_DirNotExists(): void
    {
        $nonExistentDir = __DIR__ . DIRECTORY_SEPARATOR . 'nonExistentDir';
        $foundFiles     = [];
        DirHelper::walk($nonExistentDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        });
        $this->assertCount(0, $foundFiles);
    }

    // 测试遍历目录功能，递归遍历子目录
    public function testWalk_Recursive()
    {
        $subDir = __DIR__ . DIRECTORY_SEPARATOR . 'subDir';

        if (is_dir($subDir)) {
            DirHelper::removeDir($subDir);
        }

        mkdir($subDir, 0777, true);
        $subFilePath = $subDir . DIRECTORY_SEPARATOR . 'subTestFile.txt';
        file_put_contents($subFilePath, 'test content');
        $foundFiles = [];
        DirHelper::walk($subDir, static function ($file) use (&$foundFiles) {
            $foundFiles[] = $file;
        }, true);
        $this->assertCount(1, $foundFiles);
        $this->assertEquals($subFilePath, $foundFiles[0]);
    }

    // 测试删除目录功能，正常情况
    public function testRemoveDir_HappyPath(): void
    {
        $subDir = __DIR__ . DIRECTORY_SEPARATOR . 'subDir';
        if (!is_dir($subDir)) {
            mkdir($subDir, 0777, true);
        }

        $filePath = $subDir . DIRECTORY_SEPARATOR . 'testFile.txt';
        file_put_contents($filePath, 'test content');
        $this->assertDirectoryExists($subDir);
        $this->assertFileExists($filePath);
        DirHelper::removeDir($subDir);
        $this->assertDirectoryDoesNotExist($subDir);
        $this->assertFileDoesNotExist($filePath);
    }

    // 测试删除目录功能，目录不存在
    public function testRemoveDir_DirNotExists(): void
    {
        $nonExistentDir = __DIR__ . DIRECTORY_SEPARATOR . 'nonExistentDir';
        $this->assertDirectoryDoesNotExist($nonExistentDir);
        DirHelper::removeDir($nonExistentDir); // 预期不抛出异常
        $this->assertDirectoryDoesNotExist($nonExistentDir);
    }
}