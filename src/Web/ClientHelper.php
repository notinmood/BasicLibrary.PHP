<?php

namespace Hiland\Web;

/**
 * 获取操作系统，浏览器，语言，IP，IP归属地等客户端信息
 * @author  然
 * @version 20131225
 */
class ClientHelper
{
    /**
     * 获得访客浏览器类型
     * 返回类型为以下字符串：
     * MSIE、Weixin、Firefox、Chrome、Safari、Opera、Other
     */
    public static function getBrowserName(): string
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $br = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/MSIE/i', $br)) {
                $br = 'MSIE';
            } elseif (preg_match('/MicroMessenger/i', $br)) { //因为微信浏览器使用的chrome，因此判断微信浏览器要放在判断Chrome前面
                $br = 'Weixin';
            } elseif (preg_match('/Firefox/i', $br)) {
                $br = 'Firefox';
            } elseif (preg_match('/Chrome/i', $br)) {
                $br = 'Chrome';
            } elseif (preg_match('/Safari/i', $br)) {
                $br = 'Safari';
            } elseif (preg_match('/Opera/i', $br)) {
                $br = 'Opera';
            } else {
                $br = 'Other';
            }
            return $br;
        } else {
            return "unknown";
        }
    }

    /**
     * 判断是不是微信浏览器
     * @return bool
     */
    public static function isWeixinBrowser(): bool
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 获得访客浏览器语言
     * @return string
     */
    public static function getLanguage(): string
    {
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $lang = substr($lang, 0, 5);
            if (preg_match("/zh-cn/i", $lang)) {
                $lang = "简体中文";
            } elseif (preg_match("/zh/i", $lang)) {
                $lang = "繁体中文";
            } else {
                $lang = "English";
            }
            return $lang;
        } else {
            return "unknown";
        }
    }

    /**
     * 获取访客操作系统
     * @return string
     */
    public static function getOS(): string
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $OS = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/win/i', $OS)) {
                $OS = 'Windows';
            } elseif (preg_match('/mac/i', $OS)) {
                $OS = 'MAC';
            } elseif (preg_match('/linux/i', $OS)) {
                $OS = 'Linux';
            } elseif (preg_match('/unix/i', $OS)) {
                $OS = 'Unix';
            } elseif (preg_match('/bsd/i', $OS)) {
                $OS = 'BSD';
            } else {
                $OS = 'Other';
            }
            return $OS;
        } else {
            return "unknown";
        }
    }

    /**
     * 获取客户端ip
     * @return string
     */
    public static function getIP(): string
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
                    $ip = getenv("REMOTE_ADDR");
                } else
                    if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $ip = "unknown";
                    }
        return ($ip);
    }

    // /**
    // * 获得访客真实ip
    // * @return unknown
    // */
    // static function GetIPAddress(){
    // if(!empty($_SERVER["HTTP_CLIENT_IP"])){
    // $ip = $_SERVER["HTTP_CLIENT_IP"];
    // }
    // if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //获取代理ip
    // $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
    // }
    // if($ip){
    // $ips = array_unshift($ips,$ip);
    // }
    // $count = count($ips);
    // for($i=0;$i<$count;$i++){
    // if(!preg_match("/^(10|172\.16|192\.168)\./i",$ips[$i])){//排除局域网ip
    // $ip = $ips[$i];
    // break;
    // }
    // }
    // $tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR'];
    // if($tip=="127.0.0.1"){ //获得本地真实IP
    // return self::get_onlineip();
    // }
    // else{
    // return $tip;
    // }
    // }

    // /**
    //  * 根据ip获得访客所在地地名信息
    //  * @param string $ip
    //  * @return string|boolean
    //  */
    // public static function getPlaceFromIP($ip = '')
    // {
    //     if (empty($ip)) {
    //         $ip = self::getOnlineIP();
    //     }
    //     $ip_json = @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip); // 根据taobao ip
    //     $ip_arr = json_decode(stripslashes($ip_json), 1);
    //     if ($ip_arr['code'] == 0) {
    //         return $ip_arr;
    //     } else {
    //         return false;
    //     }
    // }

    // /**
    //  * 获得本地真实IP
    //  */
    // public static function getOnlineIP()
    // {
    //     $ip_json = @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=myip");
    //     $ip_arr = json_decode(stripslashes($ip_json), 1);
    //     return $ip_arr['code'] == 0 ? $ip_arr['data']['ip'] : '';
    // }
}
