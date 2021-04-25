<?php


namespace Hiland\Utils\Web;


use think\Request;

class RequestHelper
{
    public static function isPost()
    {
        if (Request::instance()->isPost()) {
            return true;
        } else {
            return false;
        }
    }

    public static function isGet(){
        if (Request::instance()->isGet()) {
            return true;
        } else {
            return false;
        }
    }
}