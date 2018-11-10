<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/9
 * Time: 18:27
 */

namespace Hiland\Biz\Tencent;

use Hiland\Biz\Tencent\Common\MiniProgramConfig;
use Hiland\Utils\Web\NetHelper;

class MiniProgramHelper
{
    /** 根据微信小程序wx.login中success中返回的code获取 用户session信息
     * @param $code
     * @return json
     */
    public static function getUserSession($code){
        $APPID= MiniProgramConfig::getAPPID();
        $SECRET= MiniProgramConfig::getSECRET();
        $url= "https://api.weixin.qq.com/sns/jscode2session?appid=$APPID&secret=$SECRET&js_code=$code&grant_type=authorization_code";
        $result= NetHelper::request($url);
        //dump($result);
        return $result;
    }

    /**
     * 获取微信小程序用户的openid
     * @param $code
     * @return mixed
     */
    public static function getOpenID($code){
        $sessionJSON= self::getUserSession($code);
        $sessionObject= json_decode($sessionJSON);
        return $sessionObject->openid;
    }

    public static function getDecryptData(){

    }
}