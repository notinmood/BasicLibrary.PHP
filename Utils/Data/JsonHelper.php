<?php
/**
 * @file   : JsonHelper.php
 * @time   : 11:32
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

class JsonHelper
{
    /**
     * 将json字符串转换成 array 数组
     * @param string $jsonString
     * @return mixed
     */
    public static function convertToArray(string $jsonString)
    {
        return json_decode($jsonString, true);
    }

    /**
     * 将json字符串转换成object对象
     * @param $jsonString
     * @return mixed
     */
    public static function convertToObject($jsonString)
    {
        return json_decode($jsonString, false);
    }
}