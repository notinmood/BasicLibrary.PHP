<?php
/**
 * @file   : functions.php
 * @time   : 18:51
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

/**
 * 将经常使用到的类型中的方法进行提取出来形成函数,便于使用.
 */

use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\ThinkHelper;

/**
 * 将var_dump进行简短表示
 */
if (!function_exists('dump') && ThinkHelper::isThinkPHP() == false) {
    function dump($value)
    {
        var_dump($value);
    }
}

/**
 * 判断目标标的是否存在
 */
if (!function_exists("exist")) {
    function exist($value)
    {
        return ObjectHelper::isExist($value);
    }
}
