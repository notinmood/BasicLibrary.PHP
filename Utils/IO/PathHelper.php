<?php
/**
 * @file   : PathHelper.php
 * @time   : 11:42
 * @date   : 2021/9/5
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\IO;

use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\StringHelper;

class PathHelper
{
    /**
     * 将各个部分路径整合成一个完整的路径
     * @param ...$paths
     * @return string
     */
    public static function combine(...$paths)
    {
        $result = "";
        for ($i = 0; $i < count($paths); $i++) {
            $currentNode = $paths[$i];
            if (StringHelper::isEndWith($currentNode, "\\") || StringHelper::isEndWith($currentNode, "/")) {
                $len = ObjectHelper::getLength($currentNode);
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
}