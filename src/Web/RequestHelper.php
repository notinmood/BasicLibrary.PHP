<?php

namespace Hiland\Web;


use Hiland\Data\ThinkHelper;
use Hiland\Environment\EnvHelper;
use think\Container;
use think\Request;

/**
 * 请求辅助类
 */
class RequestHelper
{
    /**
     * 判断当前是否为post请求
     * @return int|null
     */
    public static function isPost(): ?int
    {
        $requestEntity = self::getRequestEntity();
        if ($requestEntity) {
            return $requestEntity->isPost();
        } else {
            return ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
        }
    }

    /**
     * 判断当前是否为get请求
     * @return bool
     */
    public static function isGet(): ?bool
    {
        $requestEntity = self::getRequestEntity();
        if ($requestEntity) {
            return $requestEntity->isGet();
        } else {
            return $_SERVER['REQUEST_METHOD'] == 'GET';
        }
    }

    /**
     * 从请求内获取数据
     * @param string $name 数据的名称
     * @param mixed|null $default 数据的缺省值
     * @param string|RequestMethods $method 请求的方式(get、post等)
     * @return mixed
     */
    public static function getInput(string $name, mixed $default = null, string|RequestMethods $method = RequestMethods::ALL): mixed
    {
        if (ThinkHelper::isThinkPHP() && function_exists("input")) {
            return input($name, $default);
        } else {
            switch ($method) {
                case RequestMethods::GET:
                    $result = $_GET[$name];
                    break;
                case RequestMethods::POST:
                    $result = $_POST[$name];
                    break;
                case RequestMethods::COOKIE:
                    $result = $_COOKIE[$name];
                    break;
                case RequestMethods::SESSION:
                    $result = $_SESSION[$name];
                    break;
                case RequestMethods::SERVER:
                    $result = $_SERVER[$name];
                    break;
                default:
                    $result = $_GET[$name];
                    if ($result === null) {
                        $result = $_POST[$name];
                    }

                    if ($result === null) {
                        $result = $_POST[$name];
                    }

                    if ($result === null) {
                        $result = $_COOKIE[$name];
                    }

                    if ($result === null) {
                        $result = $_SESSION[$name];
                    }

                    if ($result === null) {
                        $result = $_SERVER[$name];
                    }
            }

            return $result ?? $default;
        }
    }

    /**
     * 获取当前请求的原始输入数据（与getPhpInput（）互为别名）
     * @return string
     */
    public static function getInputAll(): string
    {
        return file_get_contents("php://input");
    }

    /**
     * 获取当前请求的原始输入数据（与getInputAll（）互为别名）
     * @return string
     */
    public static function getPhpInput(): string
    {
        return file_get_contents("php://input");
    }

    /**
     * @return mixed
     */
    private static function getRequestEntity(): mixed
    {
        if (EnvHelper::isThinkPHP()) {
            if (class_exists("think\Container")) {
                $requestEntity = Container::get("request");
            } else {
                $requestEntity = Request::instance();
            }
            return $requestEntity;
        } else {
            return null;
        }
    }

    /**
     * 获取当前请求的全路径
     * @return string
     */
    public static function getFullPath(): string
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $hostName  = WebHelper::getHostName();
        return $http_type . $hostName . $_SERVER['REQUEST_URI'];
    }
}
