<?php
/**
 * @file   : JsonHelper.php
 * @time   : 11:32
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Data;

class JsonHelper
{
    /**
     * 将json字符串转换成array数组
     * @param $jsonData
     * @return mixed
     */
    public static function convertToArray($jsonData)
    {
        return json_decode($jsonData,true);
    }

    /**
     * 将json字符串转换成object对象
     * @param $jsonData
     * @return mixed
     */
    public static function convertToObject($jsonData)
    {
        return json_decode($jsonData,false);
    }
}