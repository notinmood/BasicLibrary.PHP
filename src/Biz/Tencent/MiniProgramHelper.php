<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/9
 * Time: 18:27
 */

namespace Hiland\Biz\Tencent;

use Hiland\Biz\Logger\CommonLogger;
use Hiland\Biz\Tencent\MiniProgram\MiniProgramConfig;
use Hiland\Biz\Tencent\MiniProgram\WXBizDataCrypt;
use Hiland\Web\HttpClientHelper;

/**
 *
 */
class MiniProgramHelper
{
    private static $userSessionData = '';
    private static $oldCode = '';

    /** 根据微信小程序wx.login中success中返回的code获取 用户session信息(包括session_key,openid)
     * @param $code
     * @return json
     */
    public static function getUserSessionData($code)
    {
        if (empty(self::$userSessionData)) {
            self::getUserSessionDataInFact($code);
        } else {
            if ($code != self::$oldCode) {
                self::getUserSessionDataInFact($code);
            }
        }

        self::$oldCode = $code;
        return self::$userSessionData;
    }

    private static function getUserSessionDataInFact($code)
    {
        $APPID = MiniProgramConfig::getAPPID();
        $SECRET = MiniProgramConfig::getSECRET();
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$APPID&secret=$SECRET&js_code=$code&grant_type=authorization_code";
        $result = HttpClientHelper::request($url);
        self::$userSessionData = $result;
    }

    /**
     * 获取微信小程序用户的openid
     * @param $code
     * @return mixed
     */
    public static function getOpenID($code)
    {
        $sessionJSON = self:: getUserSessionData($code);
        $sessionObject = json_decode($sessionJSON);
        return $sessionObject->openid;
    }

    /**
     * 获取微信小程序用户的session_key
     * @param $code
     * @return mixed
     */
    public static function getSessionKey($code)
    {
        $sessionJSON = self:: getUserSessionData($code);
        $sessionObject = json_decode($sessionJSON);
        return $sessionObject->session_key;
    }

    /**
     * @param $encryptedData 需要解密的数据
     * @param $iv 加密偏移量
     * @param $sessionkey 通过wx.login获取到的code，然后解析出来的session_key
     * @param string $appID 小程序id
     * @return array 结构如下
     * $result['status']= 'ok'; //状态为ok或者error
     * $result['AddonInfo']= '';//如果失败了，记录失败的原因
     * $result['mainData']= $data;  //解密后的数据
     */
    public static function Decrypt($encryptedData, $iv, $sessionKey, $appID = '')
    {
        if (empty($appID)) {
            $appID = MiniProgramConfig::getAPPID();
        }

        $pc = new WXBizDataCrypt($appID, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        $result = array();
        if ($errCode == 0) {
            $result['status'] = 'ok';
            $result['AddonInfo'] = '';
            $result['mainData'] = $data;
        } else {
            $result['status'] = 'error';
            $result['AddonInfo'] = $errCode;
            $result['mainData'] = '';
        }

        return $result;
    }
}
