<?php

namespace Hiland\Web;

class HttpUtilHelper
{
    /**
     * 去除 URL 中的域名部分
     * @param string $url
     * @return string
     */
    public static function stripDomain(string $url): string
    {
        // 解析 URL
        $parts = parse_url($url);
        if ($parts === false) {
            return '';
        }

        // 重新拼装：只保留 path、query、fragment
        $result = ($parts['path'] ?? '') .
            (isset($parts['query']) ? "?{$parts['query']}" : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');

        // 保证根路径返回 “/” 而非空串
        return $result === '' ? '/' : $result;
    }

    /**
     * 获取 URL 的域名部分
     * @param string $url
     * @return string
     */
    public static function getDomain(string $url): string
    {
        // 解析 URL
        $parts = parse_url($url);
        if ($parts === false) {
            return '';
        }

        // scheme（协议）
        $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';

        // host（域名）
        $host = $parts['host'] ?? '';

        // port（端口，可选）
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        return $scheme . $host . $port;
    }
}