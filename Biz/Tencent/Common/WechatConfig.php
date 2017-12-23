<?php
namespace Hiland\Biz\Tencent\Common;

class WechatConfig
{
    // =======【基本信息设置】=====================================
    // 需要在程序根目录index.php中配置系统的物理根目录 define('PHYSICAL_ROOT_PATH', dirname(__FILE__));

    /**
     * TODO: 修改这里配置为您自己申请的商户信息
     * 微信公众号信息配置
     *
     * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
     *
     * MCHID：商户号（必须配置，开户邮件中可查看）
     *
     * MCHKEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
     * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
     *
     * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
     * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
     *
     * @var string
     */
    const APPID = 'wxb56d491f1c701ce8';
    const APPSECRET = '4c38121045c8d07b7aa040b98d4ae8d2';

    // 微信二次开发入口token
    const GATETOKEN = 'bigseaguall20160608';
    const AESKEY= 'eP2HiesIDhtHEW8fW2K9SfoSJ5M8aDsxPlPtO9aKg1T';

    const MCHID = '1310236601';
    const MCHKEY = 'eP2HiesIDhtHEW8fW2K9SfoSJ5M8aDsxPlPtO9aKg1T';

    // 微信公众平台商户的名称
    const MCHNAME = '富地流油';
    const NOTIFYURL = 'http://app.rainytop.com/jnqp/index.php/tencent/pay/notify';

    // =======【证书路径设置】=====================================
    /**
     * 设置商户证书的绝对物理路径
     * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
     * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
     */
    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     *
     * @var string
     */
    const CURL_PROXY_HOST = "0.0.0.0";

    // =======【curl代理设置】===================================
    const CURL_PROXY_PORT = 0;
    // "10.152.18.220";
    /**
     * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
     * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
     * 开启错误上报。
     * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
     *
     * @var int
     */
    const REPORT_LEVENL = 1;
    // 8080;

    // =======【上报信息配置】===================================

    /**
     * CERT 证书的物理绝对路径
     *
     * @param bool $endWithSlash
     *            路径结尾是否带斜线
     * @return string
     */
    public static function CERTABSOLUTELYPATH($endWithSlash = false)
    {
        $patharray = array(
            'ThinkPHP',
            'Library',
            'Vendor',
            'Hiland',
            'Biz',
            'Tencent',
            'Common',
            'cert'
        );

        $path = join(DIRECTORY_SEPARATOR, $patharray);
        $path = PHYSICAL_ROOT_PATH . DIRECTORY_SEPARATOR . $path;

        if ($endWithSlash) {
            $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }
}