<?php


namespace Hiland\Utils\Web;


use think\Container;
use think\Request;

class RequestHelper
{
    public static function isPost()
    {
        if (Container::get("request")->isPost()) {
            return true;
        } else {
            return false;
        }
    }

    public static function isGet(){
        if (Container::get("request")->isGet()) {
            return true;
        } else {
            return false;
        }
    }

}