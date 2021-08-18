<?php

namespace Hiland\Utils\Web;


use think\Container;
use think\Request;

class RequestHelper
{
    public static function isPost()
    {
        $requestEntity = self::getRequestEntity();

        if ($requestEntity->isPost()) {
            return true;
        } else {
            return false;
        }
    }

    public static function isGet()
    {
        $requestEntity = self::getRequestEntity();

        if ($requestEntity->isGet()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    private static function getRequestEntity()
    {
        if (class_exists("thinkContainer")) {
            $requestEntity = Container::get("request");
        } else {
            $requestEntity = Request::instance();
        }
        return $requestEntity;
    }
}