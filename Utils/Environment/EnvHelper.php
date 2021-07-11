<?php

namespace Hiland\Utils\Environment;


use Hiland\Utils\Data\RegexHelper;
use Hiland\Utils\Data\StringHelper;
use Hiland\Utils\Web\HttpResponseHeader;

class EnvHelper
{
    /**获取系统信息
     * @return array
     */
    public static function getAllInfo()
    {
        return [
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            '主机名' => $_SERVER['SERVER_NAME'],
            '服务器CPU信息' => $_SERVER['PROCESSOR_IDENTIFIER'],
            '服务器系统目录' => $_SERVER['SystemRoot'],
            'WEB服务端口' => $_SERVER['SERVER_PORT'],
            '网站文档目录' => $_SERVER["DOCUMENT_ROOT"],
            '浏览器信息' => substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
            '通信协议' => $_SERVER['SERVER_PROTOCOL'],
            '请求方法' => $_SERVER['REQUEST_METHOD'],
            'PHP版本' => phpversion(),
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '用户的IP地址' => $_SERVER['REMOTE_ADDR'],
            '剩余空间' => round((disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            'MySQL数据库持续连接' => get_cfg_var("mysql.allow_persistent") ? "是 " : "否",
            '脚本运行占用最大内存' => get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : "无",
            '当前进程用户名' => Get_Current_User(),
        ];
    }

    /**
     * 是否运行在浏览器/服务器模式下
     * @return bool
     */
    public static function isCGI()
    {
        if (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否运行在命令行模式下
     * @return bool
     */
    public static function isCLI()
    {
        $sapi_type = php_sapi_name();
        if (isset($sapi_type) && substr($sapi_type, 0, 3) == 'cli') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否运行在windows系统内
     * @return bool
     */
    public static function isWIN()
    {
        $pos = strpos(PHP_OS, 'WIN');

        if ($pos >= 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断当前服务器系统
     * @return string
     */
    public static function getOS()
    {
        if (PATH_SEPARATOR == ':') {
            return 'Linux';
        } else {
            return 'Windows';
        }
    }

    /**
     * 获取数字表示的php版本号（主版本号用整数表示，其他版本号在小数点后面排列）
     * @return number
     */
    public static function getPHPVersion()
    {
        $version = '';
        $array = explode('.', PHP_VERSION);
        foreach ($array as $k) {
            if (!StringHelper::isContains($version, '.')) {
                $version .= $k . '.';
            } else {
                $version .= $k;
            }
        }
        return (float)$version;
    }

    /**
     * 获取Web服务器名称（IIS还是apache等）
     * @return mixed
     */
    public static function getWebServerName()
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }


    /**
     * 获取托管平台的名称
     * @return string
     */
    public static function getDepositoryPlateformName()
    {
        // 自动识别SAE环境
        if (function_exists('saeAutoLoader')) {
            return 'sae';
        } else {
            return 'file';
        }
    }

    /**
     * 判断是否为本地服务器
     * @param $domainNameOrIP string 域名或ip地址
     * @return bool
     */
    public static function isLocalServer($domainNameOrIP)
    {
        //去掉后面的端口信息
        if(StringHelper::isContains($domainNameOrIP,":")){
            $domainNameOrIP= StringHelper::getStringBeforeSeperator($domainNameOrIP,":");
        }

        $isIP = preg_match(RegexHelper::IP, $domainNameOrIP);
        $isLocal = false;
        if ($isIP) {
            if (self::isPrivateIP($domainNameOrIP)) {
                return true;
            } else {
                return false;
            }
        } else {
            $domainNameOrIP = strtolower($domainNameOrIP);
            switch ($domainNameOrIP) {
                case 'localhost':
                    return true;
                default:
                    return false;
            }
        }
    }

    /**
     * 判断是否为内网ip地址，ip格式必须为 ***.***.***.***,否则为其他格式则此方法返回true
     * @param $ip string ip格式必须为 ***.***.***.***,否则为其他格式则此方法返回true
     * @return bool
     */
    public static function isPrivateIP($ip)
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * 获取服务器域名 （例如app.rainytop.com）
     * @return string
     */
    public static function getServerHostName()
    {
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        return $host;
    }

    public static function getServerCompressType($url)
    {
        $result = HttpResponseHeader::get($url, "Content-Encoding");

        return $result;
    }
}