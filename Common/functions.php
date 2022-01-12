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
use Hiland\Utils\Environment\EnvHelper;
use Hiland\Utils\Web\ServerHelper;


if (!function_exists('dump') && ThinkHelper::isThinkPHP() == false) {
    /**
     * 将var_dump进行简短表示
     * @param      $value
     * @param bool $appendNewLineSymbol 是在末尾添加一个换行标志(缺省true)
     */
    function dump($value, $appendNewLineSymbol = true)
    {
        var_dump($value);
        if ($appendNewLineSymbol == true) {
            echo EnvHelper::getNewLineSymbol();
        }
    }
}


if (!function_exists("el")) {
    /**
     * 用 echo 输出内容和一个新行标志
     * @param $value
     * @return void
     */
    function el($value)
    {
        echo $value . EnvHelper::getNewLineSymbol($value);
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

if (!function_exists("getLength")) {
    /**
     * 显示目标对象的长度(内部调用ObjectHelper::getLength()方法实现)
     * @param $data
     * @return false|int
     */
    function getLength($data)
    {
        return ObjectHelper::getLength($data);
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
