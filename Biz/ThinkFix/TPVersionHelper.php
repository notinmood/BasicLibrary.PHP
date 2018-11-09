<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/9
 * Time: 19:05
 */

namespace Hiland\Biz\ThinkFix;
class TPVersionHelper
{
    /**获取thinkphp的主版本号
     * @return int
     */
    public static function getPrimaryVersion()
    {
        return self::getSomeSubVersion(0);
    }

    /**获取thinkphp的次版本号
     * @return int
     */
    public static function getSecondaryVersion()
    {
        return self::getSomeSubVersion(1);
    }


    /**获取thinkphp的修订版本号
     * @return int
     */
    public static function getRevisionVersion()
    {
        return self::getSomeSubVersion(2);
    }


    private static function getSomeSubVersion($pos)
    {
        $result = 0;
        if (THINK_VERSION) {
            $arr = explode(".", THINK_VERSION);
            $result = $arr[$pos];
        }
        return $result;
    }
}