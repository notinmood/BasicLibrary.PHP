<?php

namespace Hiland\Biz\Tencent\Pay;

use Hiland\Biz\Tencent\Common\WechatConfig;
use Hiland\Biz\Tencent\Common\WechatException;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseBizPayUrl;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseCloseOrder;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseDownloadBill;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseMicroPay;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseOrderQuery;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseRefund;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseRefundQuery;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseReport;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseResults;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseReverse;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseShortUrl;
use Hiland\Biz\Tencent\Pay\WxPayData\WxPayDataBaseUnifiedOrder;
use Hiland\Web\HttpClientHelper;

/**
 *
 * 接口访问类，包含所有微信支付API列表的封装，类中方法为static方法，
 * 每个接口有默认超时时间（除提交被扫支付为10s，上报超时时间为1s外，其他均为6s）
 *
 * @author widyhu *
 */
class WxPayApi
{
    /**
     *
     * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseUnifiedOrder $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function unifiedOrder($inputObj, $timeOut = 10)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        // 检测必填参数
        if (!$inputObj->IsOut_trade_noSet()) {
            throw new WechatException("缺少统一支付接口必填参数out_trade_no！");
        } else {
            if (!$inputObj->IsBodySet()) {
                throw new WechatException("缺少统一支付接口必填参数body！");
            } else {
                if (!$inputObj->IsTotal_feeSet()) {
                    throw new WechatException("缺少统一支付接口必填参数total_fee！");
                } else {
                    if (!$inputObj->IsTrade_typeSet()) {
                        throw new WechatException("缺少统一支付接口必填参数trade_type！");
                    }
                }
            }
        }

        // 关联参数
        if ($inputObj->GetTrade_type() == "JSAPI" && !$inputObj->IsOpenidSet()) {
            throw new WechatException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
        }
        if ($inputObj->GetTrade_type() == "NATIVE" && !$inputObj->IsProduct_idSet()) {
            throw new WechatException("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！");
        }

        // 异步通知url未设置，则使用配置文件中的url
        if (!$inputObj->IsNotify_urlSet()) {
            $inputObj->SetNotify_url(WechatConfig::NOTIFYURL); // 异步通知url
        }

        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']); // 终端ip
        // $inputObj->SetSpbill_create_ip("1.1.1.1");
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        // 签名
        $inputObj->SetSign();
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, false, $timeOut);

        $response = HttpClientHelper::request($url, $xml, $timeOut);

        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     *
     * 产生随机字符串，不长于32位
     *
     * @param int $length
     * @return string 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond()
    {
        // 获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }

    /**
     *
     * 上报数据， 上报的时候将屏蔽所有异常流程
     *
     * @param string $url
     * @param int $startTimeStamp
     * @param array $data
     */
    private static function reportCostTime($url, $startTimeStamp, $data)
    {
        // 如果不需要上报数据
        if (WechatConfig::REPORT_LEVENL == 0) {
            return;
        }
        // 如果仅失败上报
        if (WechatConfig::REPORT_LEVENL == 1 && array_key_exists("return_code",
                $data) && $data["return_code"] == "SUCCESS" && array_key_exists("result_code",
                $data) && $data["result_code"] == "SUCCESS") {
            return;
        }

        // 上报逻辑
        $endTimeStamp = self::getMillisecond();
        $objInput = new WxPayDataBaseReport();
        $objInput->SetInterface_url($url);
        $objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
        // 返回状态码
        if (array_key_exists("return_code", $data)) {
            $objInput->SetReturn_code($data["return_code"]);
        }
        // 返回信息
        if (array_key_exists("return_msg", $data)) {
            $objInput->SetReturn_msg($data["return_msg"]);
        }
        // 业务结果
        if (array_key_exists("result_code", $data)) {
            $objInput->SetResult_code($data["result_code"]);
        }
        // 错误代码
        if (array_key_exists("err_code", $data)) {
            $objInput->SetErr_code($data["err_code"]);
        }
        // 错误代码描述
        if (array_key_exists("err_code_des", $data)) {
            $objInput->SetErr_code_des($data["err_code_des"]);
        }
        // 商户订单号
        if (array_key_exists("out_trade_no", $data)) {
            $objInput->SetOut_trade_no($data["out_trade_no"]);
        }
        // 设备号
        if (array_key_exists("device_info", $data)) {
            $objInput->SetDevice_info($data["device_info"]);
        }

        try {
            self::report($objInput);
        } catch (WechatException $e) {
            // 不做任何处理
        }
    }

    /**
     *
     * 测速上报，该方法内部封装在report中，使用时请注意异常流程
     * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseReport $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function report($inputObj, $timeOut = 1)
    {
        $url = "https://api.mch.weixin.qq.com/payitil/report";
        // 检测必填参数
        if (!$inputObj->IsInterface_urlSet()) {
            throw new WechatException("接口URL，缺少必填参数interface_url！");
        }
        if (!$inputObj->IsReturn_codeSet()) {
            throw new WechatException("返回状态码，缺少必填参数return_code！");
        }
        if (!$inputObj->IsResult_codeSet()) {
            throw new WechatException("业务结果，缺少必填参数result_code！");
        }
        if (!$inputObj->IsUser_ipSet()) {
            throw new WechatException("访问接口IP，缺少必填参数user_ip！");
        }
        if (!$inputObj->IsExecute_time_Set()) {
            throw new WechatException("接口耗时，缺少必填参数execute_time_！");
        }
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetUser_ip($_SERVER['REMOTE_ADDR']); // 终端ip
        $inputObj->SetTime(date("YmdHis")); // 商户上报时间
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = HttpClientHelper::request($url, $xml, $timeOut);
        return $response;
    }

    /**
     *
     * 查询订单，WxPayDataBaseOrderQuery中out_trade_no、transaction_id至少填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseOrderQuery $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function orderQuery($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        // 检测必填参数
        if (!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WechatException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
        }
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = HttpClientHelper::request($url, $xml, $timeOut);
        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     *
     * 关闭订单，WxPayCloseOrder中out_trade_no必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseCloseOrder $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function closeOrder($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/closeorder";
        // 检测必填参数
        if (!$inputObj->IsOut_trade_noSet()) {
            throw new WechatException("订单查询接口中，out_trade_no必填！");
        }
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = HttpClientHelper::request($url, $xml, $timeOut);
        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     *
     * 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
     * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseRefund $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function refund($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        // 检测必填参数
        if (!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WechatException("退款申请接口中，out_trade_no、transaction_id至少填一个！");
        } else {
            if (!$inputObj->IsOut_refund_noSet()) {
                throw new WechatException("退款申请接口中，缺少必填参数out_refund_no！");
            } else {
                if (!$inputObj->IsTotal_feeSet()) {
                    throw new WechatException("退款申请接口中，缺少必填参数total_fee！");
                } else {
                    if (!$inputObj->IsRefund_feeSet()) {
                        throw new WechatException("退款申请接口中，缺少必填参数refund_fee！");
                    } else {
                        if (!$inputObj->IsOp_user_idSet()) {
                            throw new WechatException("退款申请接口中，缺少必填参数op_user_id！");
                        }
                    }
                }
            }
        }
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();
        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, true, $timeOut);
        $path = $path = WechatConfig::CERTABSOLUTELYPATH(true);

        $certfilearray = array(
            $path . 'apiclient_cert.pem',
            $path . 'apiclient_key.pem',
        );


        $response = HttpClientHelper::request($url, $xml, $timeOut, true, array(), $certfilearray);

        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     *
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
     * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseRefundQuery $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function refundQuery($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
        // 检测必填参数
        if (!$inputObj->IsOut_refund_noSet() && !$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet() && !$inputObj->IsRefund_idSet()) {
            throw new WechatException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！");
        }
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = HttpClientHelper::request($url, $xml, $timeOut);
        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     * 下载对账单，WxPayDownloadBill中bill_date为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseDownloadBill $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function downloadBill($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/downloadbill";
        // 检测必填参数
        if (!$inputObj->IsBill_dateSet()) {
            throw new WechatException("对账单接口中，缺少必填参数bill_date！");
        }
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        //$response = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = HttpClientHelper::request($url, $xml, $timeOut);
        if (substr($response, 0, 5) == "<xml>") {
            return "";
        }
        return $response;
    }

    /**
     * 提交被扫支付API
     * 收银员使用扫码设备读取微信用户刷卡授权码以后，二维码或条码信息传送至商户收银台，
     * 由商户收银台或者商户后台调用该接口发起支付。
     * WxPayWxPayMicroPay中body、out_trade_no、total_fee、auth_code参数必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseMicroPay $inputObj
     * @param int $timeOut
     * @return mixed
     * @throws WechatException
     */
    public static function micropay($inputObj, $timeOut = 10)
    {
        $url = "https://api.mch.weixin.qq.com/pay/micropay";
        // 检测必填参数
        if (!$inputObj->IsBodySet()) {
            throw new WechatException("提交被扫支付API接口中，缺少必填参数body！");
        } else {
            if (!$inputObj->IsOut_trade_noSet()) {
                throw new WechatException("提交被扫支付API接口中，缺少必填参数out_trade_no！");
            } else {
                if (!$inputObj->IsTotal_feeSet()) {
                    throw new WechatException("提交被扫支付API接口中，缺少必填参数total_fee！");
                } else {
                    if (!$inputObj->IsAuth_codeSet()) {
                        throw new WechatException("提交被扫支付API接口中，缺少必填参数auth_code！");
                    }
                }
            }
        }

        $inputObj->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']); // 终端ip
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = HttpClientHelper::request($url, $xml, $timeOut);
        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     *
     * 撤销订单API接口，WxPayReverse中参数out_trade_no和transaction_id必须填写一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseReverse $inputObj
     * @param int $timeOut
     * @return mixed
     * @throws WechatException
     */
    public static function reverse($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
        // 检测必填参数
        if (!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WechatException("撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！");
        }

        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, true, $timeOut);

        $path = WechatConfig::CERTABSOLUTELYPATH() . DIRECTORY_SEPARATOR;
        $certfilearray = array(
            $path . 'apiclient_cert.pem',
            $path . 'apiclient_key.pem',
        );

        $response = HttpClientHelper::request($url, $xml, $timeOut, true, array(), $certfilearray);
        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     *
     * 生成二维码规则,模式一生成支付二维码
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseBizPayUrl $inputObj
     * @return bool
     * @throws WechatException
     * @internal param int $timeOut
     */
    public static function bizpayurl($inputObj)
    {
        if (!$inputObj->IsProduct_idSet()) {
            throw new WechatException("生成二维码，缺少必填参数product_id！");
        }

        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetTime_stamp(time()); // 时间戳
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名

        return $inputObj->GetValues();
    }

    /**
     *
     * 转换短链接
     * 该接口主要用于扫码原生支付模式一中的二维码链接转成短链接(weixin://wxpay/s/XXXXXX)，
     * 减小二维码数据量，提升扫描速度和精确度。
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     *
     * @param WxPayDataBaseShortUrl $inputObj
     * @param int $timeOut
     * @return bool 成功时返回，其他抛异常
     * @throws WechatException
     */
    public static function shorturl($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/tools/shorturl";
        // 检测必填参数
        if (!$inputObj->IsLong_urlSet()) {
            throw new WechatException("需要转换的URL，签名用原串，传输需URL encode！");
        }
        $inputObj->SetAppid(WechatConfig::APPID); // 公众账号ID
        $inputObj->SetMch_id(WechatConfig::MCHID); // 商户号
        $inputObj->SetNonce_str(self::getNonceStr()); // 随机字符串

        $inputObj->SetSign(); // 签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond(); // 请求开始时间
        //$response = self::postXmlCurl($xml, $url, false, $timeOut);
        $response = HttpClientHelper::request($url, $xml, $timeOut);
        $result = WxPayDataBaseResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result); // 上报请求花费时间

        return $result;
    }

    /**
     *
     * 支付结果通用通知
     *
     * @param mixed $callback
     *            直接回调函数使用方法: notify(you_function);
     *            回调类成员函数方法:notify(array($this, you_function));
     *            $callback 原型为：function function_name($data){}
     * @param $msg
     * @return bool|mixed
     */
    public static function notify($callback, &$msg)
    {
        // 获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        if (empty($xml)) {
            $xml = file_get_contents("php://input");
        }
        // 如果返回成功则验证签名
        try {
            $result = WxPayDataBaseResults::Init($xml);
        } catch (WechatException $e) {
            $msg = $e->errorMessage();
            return false;
        }

        return call_user_func($callback, $result);
    }

    /**
     * 直接输出xml
     *
     * @param string $xml
     */
    public static function replyNotify($xml)
    {
        echo $xml;
    }
}

