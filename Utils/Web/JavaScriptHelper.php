<?php
namespace Hiland\Utils\Web;

class JavaScriptHelper
{
    /**
     * js 弹窗并且跳转
     *
     * @param string $message
     * @param string $url
     * @param bool $isExit 执行完js，是否退出当前请求/响应序列
     * @return string js
     */
    static public function alertNavigate($message, $url, $isExit = true)
    {
        echo "<script type='text/javascript'>alert('$message');location.href='$url';</script>";
        if ($isExit) {
            exit();
        }
    }

    /**
     * js 弹窗返回
     *
     * @param string $message
     * @param bool $isExit 执行完js，是否退出当前请求/响应序列
     * @return string js
     */
    static public function alertBack($message, $isExit = true)
    {
        echo "<script type='text/javascript'>alert('$message');history.back();</script>";
        if ($isExit) {
            exit();
        }
    }

    /**
     * 页面跳转
     *
     * @param string $url
     * @param bool $isExit 执行完js，是否退出当前请求/响应序列
     * @return string js
     */
    static public function navigate($url, $isExit = true)
    {
        echo "<script type='text/javascript'>location.href='{$url}';</script>";
        if ($isExit) {
            exit();
        }
    }

    /**
     * 弹窗关闭
     *
     * @param string $message
     * @param bool $isExit 执行完js，是否退出当前请求/响应序列
     * @return string js
     */
    static public function alertClose($message, $isExit = true)
    {
        echo "<script type='text/javascript'>alert('$message');close();</script>";
        if ($isExit) {
            exit();
        }
    }

    /**
     * 弹窗
     *
     * @param string $message
     * @param bool $isExit 执行完js，是否退出当前请求/响应序列
     * @return string js
     */
    static public function alert($message, $isExit = true)
    {
        echo "<script type='text/javascript'>alert('$message');</script>";
        if ($isExit) {
            exit();
        }
    }

}
?>