<?php

namespace Hiland\Utils\Web;

class JavaScriptHelper
{
    /**
     * js 弹窗并且跳转
     * @param string $message
     * @param string $url
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void
     */
    public static function alertNavigate(string $message, string $url, bool $isExit = true)
    {
        $content= "alert('$message');location.href='$url';";
        self::javaScriptWrapper($content, $isExit);
    }

    /**
     * js 弹窗返回
     * @param string $message
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void
     */
    public static function alertBack(string $message, bool $isExit = true)
    {
        $content= "alert('$message');history.back();";
        self::javaScriptWrapper($content, $isExit);
    }

    /**
     * 页面跳转
     * @param string $url
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void
     */
    public static function navigate(string $url, bool $isExit = true)
    {
        $content= "location.href='{$url}';";
        self::javaScriptWrapper($content, $isExit);
    }

    /**
     * 弹窗关闭
     * @param string $message
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void
     */
    public static function alertClose(string $message, bool $isExit = true)
    {
        $content= "alert('$message');close();";
        self::javaScriptWrapper($content, $isExit);
    }

    /**
     * 弹窗
     * @param string $message
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void js
     */
    public static function alert(string $message, bool $isExit = true)
    {
        $content= "alert('$message');";
        self::javaScriptWrapper($content, $isExit);
    }

    /**
     * @param string $content
     * @param bool   $isExit
     */
    private static function javaScriptWrapper(string $content, bool $isExit)
    {
        echo "<script type='text/javascript'>{$content}</script>";
        if ($isExit) {
            exit();
        }
    }
}