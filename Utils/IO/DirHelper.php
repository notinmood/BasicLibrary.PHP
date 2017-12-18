<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/12/5
 * Time: 17:53
 */

namespace Hiland\Utils\IO;


class DirHelper
{
    /**
     * @param $fullPath
     * @return bool
     */
    public static function surePathExist($fullPath)
    {
        return self::makedir($fullPath);
    }

    private static function makedir($path)
    {
        $arr = array();
        while (!is_dir($path)) {
            array_push($arr, $path);//把路径中的各级父目录压入到数组中去，直接有父目录存在为止（即上面一行is_dir判断出来有目录，条件为假退出while循环）
            $path = dirname($path);//父目录
        }
        if (empty($arr)) {//arr为空证明上面的while循环没有执行，即目录已经存在
            //echo $path,'已经存在';
            return true;
        }
        while (count($arr)) {
            $parentdir = array_pop($arr);//弹出最后一个数组单元
            mkdir($parentdir);//从父目录往下创建
        }
    }

    /**
     * 获取某目录下文件的数目
     * @param $dir
     * @return int
     */
    public static function getFileCount($dir)
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
}