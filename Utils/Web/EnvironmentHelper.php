<?php
namespace Hiland\Utils\Web;

use Hiland\Utils\Data\RegexHelper;
use Hiland\Utils\Data\StringHelper;

class EnvironmentHelper
{
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