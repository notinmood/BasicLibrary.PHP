<?php
/**
 * @file   : functions.php
 * @time   : 18:51
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

// +--------------------------------------------------------------------------
// |::说明·| 系统常用的功能性函数
// |::一一·| 将经常使用到的类型中的方法进行提取出来形成函数,便于使用.
// +--------------------------------------------------------------------------

use Hiland\Data\ObjectHelper;
use Hiland\Data\StringHelper;
use Hiland\Data\ThinkHelper;
use Hiland\Environment\EnvHelper;
use Hiland\IO\ConsoleHelper;
use Hiland\Web\ServerHelper;


if (!function_exists('dump') && !ThinkHelper::isThinkPHP()) {
    /**
     * 将var_dump进行简短表示
     * @param      $value
     * @param bool $appendNewLineSymbol 是在末尾添加一个换行标志(缺省true)
     */
    function dump($value, bool $appendNewLineSymbol = true): void
    {
        var_dump($value);
        if ($appendNewLineSymbol) {
            echo EnvHelper::getNewLineSymbol();
        }
    }
}


if (!function_exists("el")) {
    /**
     * 带换行功能的echo
     * @param mixed $data 待输出信息
     * @param bool $insertStartNewLine 如果true就在$data这一行开始前页加入一个新行标志；
     *                                 否则就只在$data这一行后加入一个新行标志。
     */
    function el(mixed $data = null, bool $insertStartNewLine = false): void
    {
        ConsoleHelper::el($data, $insertStartNewLine);
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

if (!function_exists("len")) {
    /**
     * 显示目标对象的长度(内部调用ObjectHelper::getLength()方法实现)
     * @param $data
     * @return int
     */
    function len($data): int
    {
        return ObjectHelper::getLength($data);
    }
}


if (!function_exists('fixUrl')) {
    /**
     * @param string $mcaUrl 普通 url 或者 MCA 格式表示的 url("Module/Controller/Action")
     * @param string $entry 入口页面
     * @return string
     */
    function fixUrl(string $mcaUrl, string $entry = "index.php"): string
    {
        $webRoot = ServerHelper::getWebRoot();
        if (!StringHelper::isStartWith($webRoot, "/")) {
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

        if (!StringHelper::isStartWith($targetUrl, $enterUrl)) {
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
