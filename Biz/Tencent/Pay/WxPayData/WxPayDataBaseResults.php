<?php
namespace Hiland\Biz\Tencent\Pay\WxPayData;

use Hiland\Biz\Tencent\Common\WechatException;

/**
 *
 * 接口调用结果类
 *
 * @author widyhu
 *
 */
class WxPayDataBaseResults extends WxPayDataBase
{

    /**
     *
     * 使用数组初始化对象
     *
     * @param array $array
     * @param bool $noCheckSign
     * @return WxPayDataBaseResults
     * @throws WechatException
     */
    public static function InitFromArray($array, $noCheckSign = false)
    {
        $obj = new self();
        $obj->FromArray($array);
        if ($noCheckSign == false) {
            $obj->CheckSign();
        }
        return $obj;
    }

    /**
     *
     * 使用数组初始化
     *
     * @param array $array
     */
    public function FromArray($array)
    {
        $this->values = $array;
    }

    /**
     * 检测签名
     */
    public function CheckSign()
    {
        // fix异常
        if (!$this->IsSignSet()) {
            throw new WechatException("签名错误！");
        }

        $sign = $this->MakeSign();
        if ($this->GetSign() == $sign) {
            return true;
        }
        throw new WechatException("签名错误！");
    }

    /**
     * 将xml转为array
     *
     * @param string $xml
     * @return mixed
     * @throws WechatException
     */
    public static function Init($xml)
    {
        $obj = new self();
        $obj->FromXml($xml);
        // fix bug 2015-06-29
        if ($obj->values['return_code'] != 'SUCCESS') {
            return $obj->GetValues();
        }
        $obj->CheckSign();
        return $obj->GetValues();
    }

    /**
     *
     * 设置参数
     *
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }
}

?>