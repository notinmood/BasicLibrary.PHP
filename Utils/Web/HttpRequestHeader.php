<?php

namespace Hiland\Utils\Web;

class HttpRequestHeader
{
    public static function getUserAgent()
    {
        return self::get('USER-AGENT');
    }

    public static function get($key)
    {
        $key = strtoupper($key);
        $headers = self::getAll();
        foreach ($headers as $k => $v) {
            if ($k == $key) {
                return $v;
            }
        }

        return false;
    }

    public static function getAll()
    {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            $keyprefix = strtoupper(substr($key, 0, 5));
            if ('HTTP_' == $keyprefix) {
                $headers[str_replace('_', '-', substr($key, 5))] = $value;
            }
        }

        if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $header['AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST'];
        } elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $header['AUTHORIZATION'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $header['CONTENT-LENGTH'] = $_SERVER['CONTENT_LENGTH'];
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $header['CONTENT-TYPE'] = $_SERVER['CONTENT_TYPE'];
        }

        return $headers;
    }

    /*
     * public static function set($key,$value){
     * $key= strtoupper($key);
     *
     * }
     */



    /**
     * 对当前文档禁用缓存
     */
    public static function setNoCache()
    {
        HttpHeaderHelper::setNoCache();
    }
}