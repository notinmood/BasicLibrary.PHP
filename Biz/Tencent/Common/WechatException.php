<?php
namespace Hiland\Biz\Tencent\Common;

/**
 * 微信支付API异常类
 *
 * @author devel
 *
 */
class WechatException extends \Exception
{

    public function errorMessage()
    {
        return $this->getMessage();
    }

    /*
     * public static function message($messageconent){
     * $e= new self($messageconent);
     * return $e->errorMessage();
     * }
     */
}