<?php

namespace Hiland\Utils\Web;


use Hiland\Utils\Data\ThinkHelper;
use Hiland\Utils\Environment\EnvHelper;
use think\Container;
use think\Request;

/**
 * 请求辅助工具
 */
class RequestHelper
{
    /**判断当前是否为post请求
     * @return int|mixed
     */
    public static function isPost()
    {
        $requestEntity = self::getRequestEntity();
        if ($requestEntity) {
            return $requestEntity->isPost();
        } else {
            return ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
        }
    }

    /**判断当前是否为get请求
     * @return bool|mixed
     */
    public static function isGet()
    {
        $requestEntity = self::getRequestEntity();
        if ($requestEntity) {
            return $requestEntity->isGet();
        } else {
            return $_SERVER['REQUEST_METHOD'] == 'GET';
        }
    }

    public static function getInput($name, $default = null, $method = RequestMethods::ALL)
    {
        if (ThinkHelper::isThinkPHP() && function_exists("input")) {
            return input($name, $default);
        } else {
            //TODO:xiedali 需要手动实现
        }
    }

    /**
     * @return mixed
     */
    private static function getRequestEntity()
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
    public static function getFullPath()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $hostName = ServerHelper::getHostName();
        return $http_type . $hostName . $_SERVER['REQUEST_URI'];
    }
}