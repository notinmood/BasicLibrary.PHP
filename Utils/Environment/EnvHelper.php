<?php

namespace Hiland\Utils\Environment;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\RegexHelper;
use Hiland\Utils\Data\StringHelper;
use Hiland\Utils\Data\ThinkHelper;

/**
 *
 */
class EnvHelper
{
    /**获取系统信息
     * @return array
     */
    public static function getAllInfo(): array
    {
        return [
            '操作系统'         => PHP_OS,
            '运行环境'         => $_SERVER["SERVER_SOFTWARE"],
            '主机名'          => $_SERVER['SERVER_NAME'],
            '服务器CPU信息'     => $_SERVER['PROCESSOR_IDENTIFIER'],
            '服务器系统目录'      => $_SERVER['SystemRoot'],
            'WEB服务端口'      => $_SERVER['SERVER_PORT'],
            '网站文档目录'       => $_SERVER["DOCUMENT_ROOT"],
            '浏览器信息'        => substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
            '通信协议'         => $_SERVER['SERVER_PROTOCOL'],
            '请求方法'         => $_SERVER['REQUEST_METHOD'],
            'PHP版本'        => phpversion(),
            'PHP运行方式'      => php_sapi_name(),
            '上传附件限制'       => ini_get('upload_max_filesize'),
            '执行时间限制'       => ini_get('max_execution_time') . '秒',
            '服务器时间'        => date("Y年n月j日 H:i:s"),
            '北京时间'         => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '服务器域名/IP'     => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '用户的IP地址'      => $_SERVER['REMOTE_ADDR'],
            '剩余空间'         => round((disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            'MySQL数据库持续连接' => get_cfg_var("mysql.allow_persistent") ? "是 " : "否",
            '脚本运行占用最大内存'   => get_cfg_var("memory_limit") ?: "无",
            '当前进程用户名'      => get_current_user(),
        ];
    }

    /**
     * 是否运行在浏览器/服务器模式下
     * @return bool
     * ════════════════════════
     * 说明：
     * 1.在iis中配置fastcgi时PHP_SAPI的返回值为cgi-fcgi,在nginx等服务器上返回的是fpm-fcgi.
     * 因此本方法判断cgi或者fcgi就可以得到结果.
     * 2.PHP 常量 PHP_SAPI 具有和 php_sapi_name() 相同的值。
     * 更多信息参考 https://www.php.net/manual/zh/function.php-sapi-name.php
     */
    public static function isCGI(): bool
    {
        /**
         * SAPI:the Server API,就是PHP跟服务器所使用的接口方式
         */
        if (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否运行在windows系统内
     * @return bool
     */
    public static function isWIN(): bool
    {
        $pos = strpos(PHP_OS, 'WIN');

        if ($pos >= 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * TODO:
     * @return bool
     */
    public static function isDebug(): bool
    {
        $result = true;
        // ConfigH

        return $result;
    }

    /**
     * 是否在ThinkPHP环境内
     */
    public static function isThinkPHP(): bool
    {
        return ThinkHelper::isThinkPHP();
    }

    /**
     * 获取换行标志(能兼容web,window本地,linux本地情形)
     * @return string
     */
    public static function getNewLineSymbol(): string
    {
        if (self::isCLI()) {
            return PHP_EOL;
        } else {
            return "<br/>";
        }
    }

    /**
     * 是否运行在命令行模式下
     * @return bool
     */
    public static function isCLI(): bool
    {
        $sapi_type = php_sapi_name();
        if (isset($sapi_type) && substr($sapi_type, 0, 3) == 'cli') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断当前服务器系统
     * @return string (返回值为Linux或者Windows)
     */
    public static function getOS(): string
    {
        if (PATH_SEPARATOR == ':') {
            return 'Linux';
        } else {
            return 'Windows';
        }
    }

    /**
     * 获取数字表示的php版本号（主版本号用整数表示，其他版本号在小数点后面排列）
     * @return float
     */
    public static function getPhpFloatVersion(): float
    {
        $version = '';
        $array   = explode('.', PHP_VERSION);
        foreach ($array as $item) {
            if (!StringHelper::isContains($version, '.')) {
                $version .= $item . '.';
            } else {
                $version .= $item;
            }
        }
        return (float)$version;
    }

    /**
     * 用int类型表示的php版本,所有的各个子版本号都用两位取整数,然后依次排列
     * 比如,5.3.6版本的返回值为 50306
     * @return int
     */
    public static function getPhpWholeVersion(): int
    {
        return PHP_VERSION_ID;
    }


    /**
     * 获取托管平台的名称
     * @return string
     */
    public static function getDepositoryPlatformName(): string
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
    public static function isLocalServer(string $domainNameOrIP): bool
    {
        //去掉后面的端口信息
        if (StringHelper::isContains($domainNameOrIP, ":")) {
            $domainNameOrIP = StringHelper::getStringBeforeSeparator($domainNameOrIP, ":");
        }

        $isIP = preg_match(RegexHelper::IP, $domainNameOrIP);

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
    public static function isPrivateIP(string $ip): bool
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }


    /**
     * 获取web项目的物理根路径
     * ————————————————————
     *因为当前文件属于类库文件(假定名称为a),
     *客户浏览器请求的页面(假定为b)
     *当用composer加载的时候a的时候,
     *a、b两个文件对应的物理文件,在根目录下是并列的存在的两个分支子目录.
     *因此可以通过以下逻辑获取到项目的根目录物理路径
     * @return string
     */
    public static function getPhysicalRootPath(): string
    {
        //当前文件的全物理路径文件名称
        $current_path = __FILE__;
        $current_path = realpath($current_path);

        //在客户浏览器里面,请求的页面的全物理路径文件名称
        $request_path = $_SERVER['SCRIPT_FILENAME'];
        $request_path = realpath($request_path);

        $current_path_array = StringHelper::explode($current_path, DIRECTORY_SEPARATOR);
        $request_path_array = StringHelper::explode($request_path, DIRECTORY_SEPARATOR);

        $current_path_length = ObjectHelper::getLength($current_path_array);
        $request_path_length = ObjectHelper::getLength($request_path_array);

        $min_length = min($current_path_length, $request_path_length);

        $root_array = [];
        for ($i = 0; $i < $min_length; $i++) {
            if ($current_path_array[$i] == $request_path_array[$i]) {
                $root_array[] = $current_path_array[$i];
            } else {
                break;
            }
        }

        $rootPath = StringHelper::implode($root_array, DIRECTORY_SEPARATOR);

        /**
         * 在实际项目中,此方法有可能是被单元测试工具加载，单元测试工具又可能也在 vendor 目录下，
         * 那么此种情况，就需要根据本文件所在的目录,移除到最后一个vendor(有可能目录其他部分还包含vendor),前面剩余的部分就是根目录。
         */
        if (StringHelper::isEndWith($rootPath, DIRECTORY_SEPARATOR . "vendor")) {
            $pos          = StringHelper::getPositions($current_path, DIRECTORY_SEPARATOR . "vendor");
            $lastPosition = 0;
            $count        = getLength($pos);

            if ($pos && $count > 0) {
                $lastPosition = $pos[$count - 1];
            }

            return mb_substr($current_path, 0, $lastPosition);
        } else {
            return $rootPath;
        }
    }

    /**
     * 获取站点的 web 根路径
     * @return string
     */
    public static function getWebRootPath(): string
    {
        $webRoot = "/";
        $appName = self::getAppName();
        if ($appName) {
            $webRoot .= $appName . "/";
        }

        return $webRoot;
    }

    /**
     * 获取应用程序的名称
     * ---------------------
     * 原理：通过比较请求页面的 web 路径和（去除掉根目录的）物理路径，得出应用程序名称
     * @return mixed|string
     */
    public static function getAppName()
    {
        $pageWebRelativePath = $_SERVER['SCRIPT_NAME'];
        if (StringHelper::isStartWith($pageWebRelativePath, "/")) {
            $pageWebRelativePath = StringHelper::subString($pageWebRelativePath, 1);
        }

        $pageWebRelativePathArray = StringHelper::explode($pageWebRelativePath, "/");
        $pageWebRelativePathArray = array_reverse($pageWebRelativePathArray);

        $rootPhysicalPath = EnvHelper::getPhysicalRootPath();
        $rootPhysicalPath = StringHelper::replace($rootPhysicalPath, "/", "\\");

        $filePhysicalFullPath     = $_SERVER["SCRIPT_FILENAME"];
        $filePhysicalFullPath     = StringHelper::replace($filePhysicalFullPath, "/", "\\");
        $filePhysicalRelativePath = StringHelper::subString($filePhysicalFullPath, StringHelper::getLength($rootPhysicalPath));

        $filePhysicalRelativePathArray = StringHelper::explode($filePhysicalRelativePath, "\\");
        $filePhysicalRelativePathArray = array_reverse($filePhysicalRelativePathArray);

        $pageWebRelativePathArrayLength = ArrayHelper::getLength($pageWebRelativePathArray);

        if (isset($filePhysicalRelativePathArray[$pageWebRelativePathArrayLength - 1])) {
            if ($pageWebRelativePathArray[$pageWebRelativePathArrayLength - 1] == $filePhysicalRelativePathArray[$pageWebRelativePathArrayLength - 1]) {
                return "";
            } else {
                return $pageWebRelativePathArray[$pageWebRelativePathArrayLength - 1];
            }
        } else {
            return $pageWebRelativePathArray[$pageWebRelativePathArrayLength - 1];
        }
    }
}
