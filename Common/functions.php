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
 * 系统常用的功能性函数
 * ────────────────────────
 * 将经常使用到的类型中的方法进行提取出来形成函数,便于使用.
 */

use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\StringHelper;
use Hiland\Utils\Data\ThinkHelper;
use Hiland\Utils\Environment\EnvHelper;
use Hiland\Utils\IO\ConsoleHelper;
use Hiland\Utils\Web\WebHelper;


if (!function_exists('dump') && ThinkHelper::isThinkPHP() == false) {
    /**
     * 将var_dump进行简短表示
     * @param      $value
     * @param bool $appendNewLineSymbol 是在末尾添加一个换行标志(缺省true)
     */
    function dump($value, bool $appendNewLineSymbol = true)
    {
        var_dump($value);
        if ($appendNewLineSymbol == true) {
            echo EnvHelper::getNewLineSymbol();
        }
    }
}


if (!function_exists("el")) {
    /**
     * 带换行功能的echo(方法echoLine的别名)
     * @param mixed $data               待输出信息
     * @param bool  $bothBeforeAndAfter 如果true就在$data这一行前后都加入新行标志;
     *                                  如果false就只在$data这一行后加入新行标志.
     */
    function el($data = "", bool $bothBeforeAndAfter = false)
    {
        ConsoleHelper::el($data, $bothBeforeAndAfter);
    }
}


if (!function_exists("exist")) {
    /**
     * 判断标的物是否存在
     * @param $value
     * @return bool
     */
    function exist($value): bool
    {
        return ObjectHelper::isExist($value);
    }
}


if (!function_exists("getLength")) {
    /**
     * 显示目标对象的长度(内部调用ObjectHelper::getLength()方法实现)
     * @param $data
     * @return int
     */
    function getLength($data): int
    {
        return ObjectHelper::getLength($data);
    }
}


if (!function_exists('fixUrl')) {
    /**
     * @param string $mcaUrl 普通 url 或者 MCA 格式表示的 url("Module/Controller/Action")
     * @param string $entry  入口页面
     * @return string
     */
    function fixUrl(string $mcaUrl, string $entry = "index.php"): string
    {
        $webRoot = WebHelper::getWebRoot();
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
            $targetUrl     = StringHelper::subString($targetUrl, $webRootLength);
            $targetUrl     = $enterUrl . $targetUrl;
        }

        return $targetUrl;
    }
}

/**
 * 以下方法不要删除，存在的目的是在单元测试内使用
 */
if (!function_exists('helloBar')) {
    function helloBar($someone): string
    {
        return "hello $someone";
    }
}