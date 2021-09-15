<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/6
 * Time: 10:07
 */

namespace Hiland\Utils\IO;


use Hiland\Utils\Web\WebHelper;

/**
 * 关于线程调用的常用方法
 * 方法asyncRun调用的参数为一个url地址
 * 方法
 */
class ThreadHelper
{
    /**
     * 不需要返回值的异步执行（只在后台执行业务逻辑，不能有返回值）
     * @param string $url string 请求地址，不包括域名信息
     * @param array  $post_data
     * @return int|void
     */
    public static function asyncRun($url, $post_data = array())
    {
        $url_array = parse_url($url);
        $hostname = $url_array['host'];
        $port = isset($url_array['port']) ? $url_array['port'] : 80;
        @$requestPath = $url_array['path'] . "?" . $url_array['query'];
        $fp = fsockopen($hostname, $port, $errno, $errstr, 10);

        if (!$fp) {
            echo "$errstr ($errno)";
            return -1;
        }

        //stream_set_blocking的第二个参数mode：如果为0，资源流将会被转换为非阻塞模式；
        //如果是1，资源流将会被转换为阻塞模式。
        // 该参数的设置将会影响到像 fgets() 和 fread() 这样的函数从资源流里读取数据。
        // 在非阻塞模式下，调用 fgets() 总是会立即返回；
        //而在阻塞模式下，将会一直等到从资源流里面获取到数据才能返回。
        stream_set_blocking($fp, 0); //非阻塞

        $method = "GET";

        if (!empty($post_data)) {
            $method = "POST";
        }

        $header = "$method $requestPath HTTP/1.1\r\n";
        $header .= "Host: $hostname\r\n";

        if (!empty($post_data)) {
            $_post = [];
            foreach ($post_data as $k => $v) {
                $_post[] = $k . "=" . urlencode($v);//必须做url转码以防模拟post提交的数据中有&符而导致post参数键值对紊乱
            }

            $_post = implode('&', $_post);
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";//POST数据
            $header .= "Content-Length: " . strlen($_post) . "\r\n";//POST数据的长度
            $header .= "Connection: Close\r\n\r\n";//长连接关闭
            $header .= $_post; //传递POST数据
        } else {
            $header .= "Connection: Close\r\n\r\n";//长连接关闭
        }

        fwrite($fp, $header);
        fclose($fp);
    }


    /**
     * @param ...$urls
     */
    public static function multiRun(...$urls)
    {
        //设置缓冲为0(也可以去php.ini设置)
        ini_set('output_buffering', 0);
        //打开输出缓冲区
        ob_start();

        //设置一个空数组
        $curl_Arr = [];

        $urlCount= count($urls);
        for ($i = 0; $i < $urlCount; $i++) {
            //开启curl连接
            $curl_Arr[$i] = curl_init($urls[$i]);
            //CURLOPT_RETURNTRANSFER 设置为1表示稍后执行的curl_exec函数的返回是URL的返回字符串，而不是把返回字符串定向到标准输出并返回TRUE；
            curl_setopt($curl_Arr[$i], CURLOPT_RETURNTRANSFER, 1);
        }

        //创建批处理cURL句柄
        $mh = curl_multi_init();

        foreach ($curl_Arr as $k => $ch) {
            //curl句柄入栈增加
            curl_multi_add_handle($mh, $ch);
        }
        $active = null;
        while (count($curl_Arr) > 0) {
            //发起curl_multi请求
            @curl_multi_exec($mh, $active);
            foreach ($curl_Arr as $k => $ch) {
                //获取句柄的返回值
                if ($result[$k] = curl_multi_getcontent($ch)) {
                    //输出结果
                    echo "$result[$k]\n";
                    ob_flush();
                    //把被释放的数据发送到浏览器
                    flush();
                    //关闭该句柄
                    curl_multi_remove_handle($mh, $ch);
                    unset($curl_Arr[$k]);
                }
            }
        }
        //关闭ouput_buffering机制
        ob_end_flush();
        //关闭"curl_mulit"句柄
        curl_multi_close($mh);
    }


    //--以下代码属于hook的逻辑----------------------------------------------------------------
    private static $hook_list = array();
    private static $hooked = false;


    /**
     * hook函数fastcgi_finish_request执行
     * 本方法在Windows 下因为没有php-fpm而无法执行。
     * @param callback $callback
     * @param array    $params
     * @example location description
     * 调用方式
     * AsyncHook::hook(array($this, 'sendEmail'), array());//面向对象调用
     * AsyncHook::hook('SmsService::sendSMS', array(trim($phone), $noticeWords));//面向过程方式调用
     */
    public static function hook($callback, $params)
    {
        self::$hook_list[] = array('callback' => $callback, 'params' => $params);
        if (self::$hooked == false) {
            self::$hooked = true;
            register_shutdown_function(array(__CLASS__, '__run'));
        }
    }



    /**
     * 由系统调用
     * @return void
     */
    public static function __run()
    {
        /**
         * fastcgi_finish_request仅仅在FPM下有效,此处是为了兼容处理
         */
        if (!function_exists("fastcgi_finish_request")) {
            function fastcgi_finish_request()
            {
            }
        }

        fastcgi_finish_request();
        if (empty(self::$hook_list)) {
            return;
        }
        foreach (self::$hook_list as $hook) {
            $callback = $hook['callback'];
            $params = $hook['params'];
            call_user_func_array($callback, $params);
        }
    }
    //--以上代码属于hook的逻辑----------------------------------------------------------------
}