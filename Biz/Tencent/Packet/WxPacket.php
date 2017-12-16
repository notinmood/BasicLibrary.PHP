<?php
namespace Hiland\Biz\Tencent\Packet;

use Hiland\Biz\Loger\CommonLoger;
use Hiland\Biz\Tencent\Common\WechatConfig;
use Hiland\Biz\Tencent\Common\WechatException;
use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\CipherHelper;
use Hiland\Utils\Web\NetHelper;
use Hiland\Utils\Web\WebHelper;

class WxPacket
{

    var $parameters;

    public function getParameter($parameter)
    {
        return $this->parameters[$parameter];
    }

    /**
     * 发送红包
     *
     * @param array $paraArray
     * @return \SimpleXMLElement[]
     */
    public function send($paraArray)
    {
        foreach ($paraArray as $k => $v) {
            $this->parameters[$k] = $v;
        }

        $postXml = $this->generatePacketXml();
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

        $path = WechatConfig::CERTABSOLUTELYPATH(true);

        $certfilearray = array(
            $path . 'apiclient_cert.pem',
            $path . 'apiclient_key.pem',
            $path . 'rootca.pem'
        );

        $responseXml = NetHelper::request($url, $postXml, 30, false, array(), $certfilearray);
        //CommonLoger::log('hongbao-allResult',$responseXml);

        $responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $responseObj->return_code;
    }

    /**
     * 生成红包接口XML信息
     * @return string 红包接口XML信息
     *         <xml>
     * <sign>![CDATA[E1EE61A9]]</sign>
     * <mch_billno>![CDATA[00100]]</mch_billno>
     * <mch_id>![CDATA[888]]</mch_id>
     * <wxappid>![CDATA[wxcbda96de0b165486]]</wxappid>
     * <nick_name>![CDATA[nick_name]]</nick_name>
     * <send_name>![CDATA[send_name]]</send_name>
     * <re_openid>![CDATA[onqOjjXXXXXXXXX]]</re_openid>
     * <total_amount>![CDATA[100]]</total_amount>
     * <min_value>![CDATA[100]]</min_value>
     * <max_value>![CDATA[100]]</max_value>
     * <total_num>![CDATA[1]]</total_num>
     * <wishing>![CDATA[恭喜发财]]</wishing>
     * <client_ip>![CDATA[127.0.0.1]]</client_ip>
     * <act_name>![CDATA[新年红包]]</act_name>
     * <act_id>![CDATA[act_id]]</act_id>
     * <remark>![CDATA[新年红包]]</remark>
     * </xml>
     */
    public function generatePacketXml()
    {
        try {
            $this->setParameter('sign', $this->getSign());
            $xml = ArrayHelper::Toxml($this->parameters, 'xml', false);
        } catch (WechatException $e) {
            die('error' . $e->errorMessage());
        }

        return $xml;
    }

    public function setParameter($parameterName, $parameterValue)
    {
        $this->parameters[$parameterName] = $parameterValue;
    }

    /**
     * 例如：
     * appid： wxd111665abv58f4f
     * mch_id： 10000100
     * device_info： 1000
     * Body： test
     * nonce_str： ibuaiVcKdpRxkhJA
     * 第一步：对参数按照 key=value 的格式，并按照参数名 ASCII 字典序排序如下：
     * stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_i
     * d=10000100&nonce_str=ibuaiVcKdpRxkhJA";
     * 第二步：拼接支付密钥：
     * stringSignTemp="stringA&key=192006250b4c09247ec02edce69f6a2d"
     * sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A
     * 9CF3B7"
     */
    protected function getSign()
    {
        $key = WechatConfig::MCHKEY;
        try {
            if ($this->checkSignParameters() == false) { // 检查生成签名参数
                throw new WechatException("生成签名参数缺失！" . "<br>");
            }

            $unSignParaString = WebHelper::formatArrayAsUrlParameter($this->parameters);
            $result = CipherHelper::signature($unSignParaString, $key);

            return $result;
        } catch (WechatException $e) {
            die($e->errorMessage());
        }
    }

    /**
     * 检查是否满足可以设置签名的条件
     *
     * @return boolean
     */
    private function checkSignParameters()
    {
        if ($this->parameters["nonce_str"] == null || $this->parameters["mch_billno"] == null || $this->parameters["mch_id"] == null || $this->parameters["wxappid"] == null || $this->parameters["nick_name"] == null || $this->parameters["send_name"] == null || $this->parameters["re_openid"] == null || $this->parameters["total_amount"] == null || $this->parameters["max_value"] == null || $this->parameters["total_num"] == null || $this->parameters["wishing"] == null || $this->parameters["client_ip"] == null || $this->parameters["act_name"] == null || $this->parameters["remark"] == null || $this->parameters["min_value"] == null) {
            return false;
        } else {
            return true;
        }
    }
}
?>