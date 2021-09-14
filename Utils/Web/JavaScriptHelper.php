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
    public static function alertNavigate($message, $url, $isExit = true)
    {
        $content= "alert('$message');location.href='$url';";
        self::javaScriptFrame($content, $isExit);
    }

    /**
     * js 弹窗返回
     * @param string $message
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void
     */
    public static function alertBack($message, $isExit = true)
    {
        $content= "alert('$message');history.back();";
        self::javaScriptFrame($content, $isExit);
    }

    /**
     * 页面跳转
     * @param string $url
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void
     */
    public static function navigate($url, $isExit = true)
    {
        $content= "location.href='{$url}';";
        self::javaScriptFrame($content, $isExit);
    }

    /**
     * 弹窗关闭
     * @param string $message
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void
     */
    public static function alertClose($message, $isExit = true)
    {
        $content= "alert('$message');close();";
        self::javaScriptFrame($content, $isExit);
    }

    /**
     * 弹窗
     * @param string $message
     * @param bool   $isExit 执行完js，是否退出当前请求/响应序列
     * @return void js
     */
    public static function alert($message, $isExit = true)
    {
        $content= "alert('$message');";
        self::javaScriptFrame($content, $isExit);
    }

    /**
     * @param $content
     * @param $isExit
     */
    private static function javaScriptFrame($content, $isExit)
    {
        echo "<script type='text/javascript'>{$content}</script>";
        if ($isExit) {
            exit();
        }
    }
}