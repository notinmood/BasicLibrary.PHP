<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/6
 * Time: 10:07
 */

namespace Hiland\Utils\IO;


use Hiland\Utils\Web\WebHelper;

class Thread
{
    /**
     * 不需要返回值的异步执行（只在后台执行业务逻辑，不能有返回值）
     * @param $url string 请求地址，不包括域名信息
     * @param string $host 域名主机信息
     * @param int $port 端口
     */
    public static function asynExec($url, $host = '', $port = 80)
    {
        if (empty($host)) {
            $host = WebHelper::getHostName();
        }

        $fp = fsockopen($host, $port, $errno, $errstr, 30);
        if (!$fp) {
            echo 'error fsockopen';
        } else {
            //stream_set_blocking的第二个参数mode：如果为0，资源流将会被转换为非阻塞模式；
            //如果是1，资源流将会被转换为阻塞模式。
            // 该参数的设置将会影响到像 fgets() 和 fread() 这样的函数从资源流里读取数据。
            // 在非阻塞模式下，调用 fgets() 总是会立即返回；
            //而在阻塞模式下，将会一直等到从资源流里面获取到数据才能返回。
            stream_set_blocking($fp, 0);
            $http = "GET $url HTTP/1.1\r\n";
            $http .= "Host: $host\r\n";
            $http .= "Connection: Close\r\n\r\n";
            fwrite($fp, $http);
            fclose($fp);
        }
    }
}