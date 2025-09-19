<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2016/12/5
 * Time: 17:53
 */

namespace Hiland\IO;

use Closure;
use Hiland\Data\StringHelper;

/**
 * 目录操作工具类
 */
class DirHelper
{
    /**
     * 确保目录存在
     * @param $fullPath
     * @return bool
     */
    public static function ensurePathExist($fullPath): bool
    {
        if (!is_dir($fullPath) && !mkdir($fullPath, 0777, true) && !is_dir($fullPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $fullPath));
        }
        return $fullPath;
    }

    /**
     * 获取某目录下文件的数目
     * @param $dir
     * @return int
     */
    public static function getFileCount($dir): int
    {
        $fileCount = 0;
        self::walk($dir, static function ($item) use (&$fileCount) {
            if (is_file($item)) {
                $fileCount++;
            }
        });

        return $fileCount;
    }

    /**
     * 确保目录以路径分隔符结尾
     * @param $dir
     * @return mixed|string
     */
    public static function ensureEndWithPathSeparator($dir): mixed
    {
        if (!StringHelper::isEndWith($dir, DIRECTORY_SEPARATOR)) {
            $dir .= DIRECTORY_SEPARATOR;
        }

        return $dir;
    }

    public static function removeDir($dir): void
    {
        //0-> 确保目录存在
        if (!is_dir($dir)) {
            return;
        }

        //1-> 删除指定目录下的所有子目录和文件
        self::walk($dir, static function ($item) {
            if (is_dir($item)) {
                rmdir($item);
            } else {
                unlink($item);
            }
        }, true);

        //2-> 删除指定目录本身
        rmdir($dir);
    }

    /**
     * 遍历目录下的子目录和文件
     * (顺序为：先处理子目录，再处理文件)
     * @param $dir
     * @param Closure $dealItemFunction 回调函数，参数为文件或子目录的绝对路径（具体为子目录还是文件，需要开发人员自行判断）
     * @param bool $isRecursive 是否递归遍历子目录
     * @return void
     */
    public static function walk($dir, Closure $dealItemFunction, bool $isRecursive = false): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            $itemFullName = $dir . DIRECTORY_SEPARATOR . $item;
            if ($isRecursive && is_dir($itemFullName)) {
                self::walk($itemFullName, $dealItemFunction, $isRecursive);
            }

            $dealItemFunction($itemFullName);
        }
    }
}
