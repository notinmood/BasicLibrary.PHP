<?php
/**
 * @file   : PathHelper.php
 * @time   : 11:42
 * @date   : 2021/9/5
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\IO;

use Hiland\Data\ObjectHelper;
use Hiland\Data\StringHelper;

/**
 * 文句路径操作工具类
 */
class PathHelper
{
    /**
     * 将各个部分路径整合成一个完整的路径
     * @param ...$paths
     * @return string
     */
    public static function combine(...$paths): string
    {
        $result = "";
        for ($i = 0, $iMax = count($paths); $i < $iMax; $i++) {
            $currentNode = $paths[$i];
            if (StringHelper::isEndWith($currentNode, "\\") || StringHelper::isEndWith($currentNode, "/")) {
                $len         = ObjectHelper::getLength($currentNode);
                $currentNode = substr($currentNode, 0, $len - 1);
            }

            if ($result) {
                $result .= DIRECTORY_SEPARATOR . $currentNode;
            } else {
                $result .= $currentNode;
            }
        }

        return $result;
    }

    /**
     * 确保目录以路径分隔符结尾
     * @param $dir
     * @return mixed|string
     */
    public function ensureEndWithPathSeparator($dir)
    {
        return DirHelper::ensureEndWithPathSeparator($dir);
    }
}
