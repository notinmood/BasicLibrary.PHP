<?php

namespace Hiland\Web;


use Hiland\Data\StringHelper;

class HttpHeaderHelper
{
    /**
     * 设置http头信息
     * @param string $value
     */
    public static function set(string $value): void
    {
        header($value);
    }


    /**
     * 页面正常状态 200 ok
     */
    public static function setPage200(): void
    {
        self::set('HTTP/1.1 200 OK');
    }


    /**
     * 设置一个 404 头: 404 文件找不到错误
     */
    public static function setPage404(): void
    {
        self::set('HTTP/1.1 404 Not Found');
    }

    /**
     * 设置地址被永久的重定向 301 重定向
     */
    public static function setPage301(): void
    {
        self::set('HTTP/1.1 301 Moved Permanently');
    }

    /**
     * 告诉浏览器文档内容没有发生改变
     */
    public static function setPage304(): void
    {
        self::set('HTTP/1.1 304 Not Modified');
    }

    /**
     * 转到一个新地址
     * @param string $url 目标地址
     */
    public static function redirectUrl(string $url): void
    {
        self::set("Location: $url");
    }

    /**
     * 延迟转向
     * @param string $url 目标地址
     * @param int $seconds 延迟时间
     */
    public static function redirectUrlDelay(string $url, int $seconds = 10): void
    {
        self::set("Refresh: $seconds; url=$url");
    }

    /**
     * 对当前文档禁用缓存
     */
    public static function setNoCache(): void
    {
        self::set("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
        self::set("Expires: Mon, 26 Jul 1997 05:00:00 GMT");// Date in the past
        self::set("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        self::set("Pragma: no-cache");
    }

    public static function setContentType($contentType = "text/html; charset=utf-8"): void
    {
        self::set(StringHelper::format("Content-Type: {?}", [$contentType]));
    }

    /**
     * 设置跨域请求
     * @param $customHeaders string 自定义的头信息(逗号分隔的字符串，如："myHeader1,myHeader2,token")
     * @return void
     */
    public static function setCors(string $customHeaders = ""): void
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS');

        $allowHeaders = "x-requested-with,content-type";
        if ($customHeaders) {
            $allowHeaders .= "," . $customHeaders;
        }
        header($allowHeaders);
    }
}
