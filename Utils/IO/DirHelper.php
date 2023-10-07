<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2016/12/5
 * Time: 17:53
 */

namespace Hiland\Utils\IO;

use Hiland\Utils\Data\StringHelper;

/**
 * 目录操作工具类
 */
class DirHelper
{
    /**
     * @param $fullPath
     * @return bool
     */
    public static function ensurePathExist($fullPath): bool
    {
        return self::makeDir($fullPath);
    }

    private static function makeDir($path)
    {
        $arr = array();
        while (!is_dir($path)) {
            $arr[] = $path;          //把路径中的各级父目录压入到数组中去，直接有父目录存在为止（即上面一行is_dir判断出来有目录，条件为假退出while循环）
            $path  = dirname($path); //父目录
        }
        if (empty($arr)) {//arr为空证明上面的while循环没有执行，即目录已经存在
            //echo $path,'已经存在';
            return true;
        }
        while (count($arr)) {
            $parentDir = array_pop($arr);//弹出最后一个数组单元
            mkdir($parentDir);           //从父目录往下创建
        }
    }

    /**
     * 获取某目录下文件的数目
     * @param $dir
     * @return int
     */
    public static function getFileCount($dir): int
    {
        $files = scandir($dir);
        $count = 0;
        foreach ($files as $file) {
            if ($file == '.' || $file == '..' || is_dir($dir . '/' . $file)) {
                //print_r($file.'|');
            } else {
                $count++;
            }
        }

        return $count;
    }

    /**
     * 确保目录以路径分隔符结尾
     * @param $dir
     * @return mixed|string
     */
    public static function ensureEndWithPathSeparator($dir)
    {
        if (!StringHelper::isEndWith($dir, DIRECTORY_SEPARATOR)) {
            $dir = $dir . DIRECTORY_SEPARATOR;
        }

        return $dir;
    }
}
