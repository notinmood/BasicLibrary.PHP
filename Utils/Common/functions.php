<?php
/**
 * @file   : functions.php
 * @time   : 18:51
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

use Hiland\Utils\Data\ThinkHelper;

if (!function_exists('dump') && ThinkHelper::isThinkPHP() == false) {
    function dump($value)
    {
        var_dump($value);
    }
}
