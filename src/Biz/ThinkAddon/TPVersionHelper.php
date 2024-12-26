<?php
// /**
//  * Created by PhpStorm.
//  * User: xiedali
//  * Date: 2018/11/9
//  * Time: 19:05
//  */
//
// namespace Hiland\Biz\ThinkAddon;
//
// use Hiland\Data\ArrayHelper;
// use Hiland\Data\ObjectHelper;
// use Hiland\Data\StringHelper;
// use think\App;
//
// class TPVersionHelper
// {
//     /**获取thinkphp的主版本号
//      * @return int
//      */
//     public static function getPrimaryVersion()
//     {
//         return self::getSomeSubVersion(0);
//     }
//
//     /**获取thinkphp的次版本号
//      * @return int
//      */
//     public static function getSecondaryVersion()
//     {
//         return self::getSomeSubVersion(1);
//     }
//
//
//     /**获取thinkphp的修订版本号
//      * @return int
//      */
//     public static function getRevisionVersion()
//     {
//         return self::getSomeSubVersion(2);
//     }
//
//     public static function getVersionAddon()
//     {
//         return self::getSomeSubVersion(3);
//     }
//
//
//     private static function getSomeSubVersion($pos)
//     {
//         $result = 0;
//         $arr = [];
//
//         if (App::VERSION) {
//             $ver = App::VERSION;
//             $firstNode = StringHelper::getStringBeforeSeperator($ver, " ");
//             $secondNode = StringHelper::getStringAfterSeperator($ver, " ");
//             $arr = explode(".", $firstNode);
//             $arr[] = $secondNode;
//         } else {
//             if (!defined(THINK_VERSION)) {
//                 $arr = explode(".", THINK_VERSION);
//             }
//         }
//
//         if (ArrayHelper::containsKey($arr, $pos)) {
//             $result = $arr[$pos];
//         }
//
//         return $result;
//     }
// }