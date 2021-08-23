<?php

namespace Hiland\Utils\Web;


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
}