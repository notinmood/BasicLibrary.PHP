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
use Hiland\Utils\Data\StringHelper;
use Hiland\Utils\Data\ThinkHelper;
use Hiland\Utils\IO\ConsoleHelper;
use Hiland\Utils\Web\ServerHelper;


if (!function_exists('dump') && ThinkHelper::isThinkPHP() == false) {
    /**
     * 将var_dump进行简短表示
     * @param $value
     */
    function dump($value)
    {
        var_dump($value);
    }
}


if (!function_exists("exist")) {
    /**
     * 判断标的物是否存在
     * @param $value
     * @return bool
     */
    function exist($value)
    {
        return ObjectHelper::isExist($value);
    }
}

if (!function_exists("el")) {
    /**
     * 使用单独一行显示文本信息
     * @param string $stringData
     * @param false  $both 是否在此行的前后都使用新行标志,缺省false仅在行尾加入新行标志
     */
    function el($stringData, $both = false)
    {
        ConsoleHelper::el($stringData, $both);
    }
}


if (!function_exists('fixUrl')) {
    /**
     * @param string $mcaUrl 普通url或者MCA格式表示的url("Module/Controller/Action")
     * @param string $entry  入口页面
     * @return string
     */
    function fixUrl($mcaUrl, $entry = "index.php")
    {
        $webRoot = ServerHelper::getWebRoot();
        if (StringHelper::isStartWith($webRoot, "/") == false) {
            $webRoot = "/" . $webRoot;
        }

        $enterUrl = $webRoot;
        if ($entry) {
            $enterUrl .= $entry . "/";
        }

        if (function_exists("url")) {
            /**
             * 如果是在thinkphp中存在url函数则调用
             */
            $targetUrl = url($mcaUrl);
        } else {
            $targetUrl = $mcaUrl;
        }

        if (StringHelper::isStartWith($targetUrl, $enterUrl) == false) {
            $webRootLength = StringHelper::getLength($webRoot);
            $targetUrl = StringHelper::subString($targetUrl, $webRootLength);
            $targetUrl = $enterUrl . $targetUrl;
        }

        return $targetUrl;
    }
}
