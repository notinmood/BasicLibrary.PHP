<?php
namespace Hiland\Biz\Tencent;

use Hiland\Biz\Loger\CommonLoger;
use Hiland\Biz\Tencent\Common\WechatConfig;
use Hiland\Utils\Data\RandHelper;
use Hiland\Utils\DataModel\ModelMate;
use Hiland\Utils\Web\NetHelper;

class WechatHelper
{

    /**
     * 获取要生成带参数的二维码所需要的票据
     *
     * @param int $key
     *            需要通过二维码传递的信息，数字类型，长效二维码取值范围为0-100000，临时二维码取正整数
     * @param string $accessToken
     *            包含了微信公众平台信息的accesstoken
     * @param string $effectType
     *            二维码的有效期类型，分为临时二维码（QR_SCENE）和长效二维码（QR_LIMIT_SCENE）
     * @param string $expireSeconds
     *            二维码的有效期（以秒为单位，默认2592000（即为30天）），此参数仅对临时二维码有效，对长效二维码无效
     */
    public static function getQRTicket($key, $accessToken = '', $effectType = 'QR_SCENE', $expireSeconds = '2592000')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $effectType = strtoupper($effectType);

        if ($effectType == 'QR_LIMIT_SCENE') { // 长效二维码
            $qrrequest = '{
                "action_name": "QR_LIMIT_SCENE",
                "action_info": {
                    "scene": {
                        "scene_id": ' . $key . '
                    }
                }
            }';
        } else { // 临时二维码
            $qrrequest = '{
                "expire_seconds": ' . $expireSeconds . ',
                "action_name": "QR_SCENE",
                "action_info": {
                    "scene": {
                        "scene_id":  ' . $key . '
                    }
                }
            }';
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $accessToken;
        $result = NetHelper::request($url, $qrrequest);//NetHelper::Post($url, $qrrequest);
        //return $result;
        $jsoninfo = json_decode($result);
        $ticket = $jsoninfo->ticket;

        return $ticket;
    }

    public static function cleanAccessTokenCache(){
        //CommonLoger::log("cleanAccessTokenCache","sssssssssssssssssss");
        $cacheKey= self::getAccessTokenCacheKey();
        S($cacheKey,null);
    }

    private static function getAccessTokenCacheKey(){
        return "weixin_accesstoken_20140225";
    }

    /**
     * 根据微信公众平台应用id和安全信息获取访问令牌
     *
     * @param string $appID
     *            微信公众平台的应用标识
     * @param string $appSecret 微信公众平台的密码
     * @param bool $useCache
     *            是否进行缓存
     * @param int $cacheSeconds
     *            缓存时间
     * @return mixed
     */
    public static function getAccessToken($appID = '', $appSecret = '', $useCache = true, $cacheSeconds = 3600)
    {
        $useCache = false;
        if (empty($appID)) {
            $appID = WechatConfig::APPID;
        }

        if (empty($appSecret)) {
            $appSecret = WechatConfig::APPSECRET;
        }

        $result = false;
        $cachekey = self::getAccessTokenCacheKey();
        if ($useCache == true) {
            $result = S($cachekey);
            if ($result != false && $result != "") {
                return $result;
            }
        }

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appID&secret=$appSecret";

        $output = NetHelper::request($url);
        // 检查错误、你可以加一段检查错误的语句（虽然这并不是必需的）
        if ($output === FALSE) {
            // 不解析返回的json信息
        } else {
            // $result = $output;
            $result = json_decode($output, true);
            $result = $result["access_token"];

            if ($useCache == true) {
                S($cachekey, $result, $cacheSeconds);
            }
        }

        return $result;
    }

    /**
     * 通过openid获取微信用户的基本信息
     *
     * @param string $accessToken
     * @param string $openID
     * @return object 返回值格式为：
     *         object(stdClass)#14 (12) {
     *         ["subscribe"] => int(1)
     *         ["openid"] => string(28) "oOjPas9Yl4uOxEEPlhQhTmvPx7dI"
     *         ["nickname"] => string(6) "白雪"
     *         ["sex"] => int(2)
     *         ["language"] => string(5) "zh_CN"
     *         ["city"] => string(6) "海淀"
     *         ["province"] => string(6) "北京"
     *         ["country"] => string(6) "中国"
     *         ["headimgurl"] => string(118) "http://wx.qlogo.cn/mmopen/PiajxSqBRaELzYkFIlPNLmPUEHTiadPrH3SYY2FfT4BprLdTYJibiaF4tNUEaIwPcUM98mNcE86WHMDY0ZfXE6eazQ/0"
     *         ["subscribe_time"] => int(1451559474)
     *         ["remark"] => string(0) ""
     *         ["groupid"] => int(0)
     *         }
     */
    public static function getUserInfo($openID, $accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openID&lang=zh_CN";
        $output = NetHelper::request($url);
        $jsoninfo = json_decode($output);
        return $jsoninfo;
    }

    /**
     * 向微信服务器上传媒体信息
     *
     * @param string $accessToken
     * @param string $mediaFileName
     * @return bool|int
     */
    public static function uploadMedia($mediaFileName, $accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $url = "http://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . $accessToken . "&type=image";
        $mediajson = array(
            "media" => "@" . $mediaFileName
        );

        $isForceUnSafe= false;
        if (version_compare(PHP_VERSION, '5.6.0', '>') && version_compare(PHP_VERSION, '7.0.0', '<')){
            $isForceUnSafe= true;
        }
        $result = NetHelper::request($url, $mediajson,0,false,null,null,$isForceUnSafe);

        $row = json_decode($result);
        $mediaId= $row->media_id;
        if($mediaId){
            return $mediaId;
        }else{
            return false;
        }
    }

    /**
     * 创建菜单
     *
     * @param string $accessToken
     * @param string $menuJson
     *            $menujson 的格式为
     *            {
     *            "button":[
     *            {
     *            "type":"click",
     *            "name":"今日歌曲",
     *            "key":"V1001_TODAY_MUSIC"
     *            },
     *            {
     *            "type":"click",
     *            "name":"歌手简介",
     *            "key":"V1001_TODAY_SINGER"
     *            },
     *            {
     *            "name":"菜单",
     *            "sub_button":[
     *            {
     *            "type":"view",
     *            "name":"搜索",
     *            "url":"http://www.soso.com/"
     *            },
     *            {
     *            "type":"view",
     *            "name":"视频",
     *            "url":"http://v.qq.com/"
     *            },
     *            {
     *            "type":"click",
     *            "name":"赞一下我们",
     *            "key":"V1001_GOOD"
     *            }]
     *            }]
     *            }
     *
     *
     *            各参数说明
     *            参数 是否必须 说明
     *            button 是 一级菜单数组，个数应为1~3个
     *            sub_button 否 二级菜单数组，个数应为1~5个
     *            type 是 菜单的响应动作类型，目前有click、view两种类型
     *            name 是 菜单标题，不超过16个字节，子菜单不超过40个字节
     *            key click类型必须 菜单KEY值，用于消息接口推送，不超过128字节
     *            url view类型必须 网页链接，用户点击菜单可打开链接，不超过256字节
     * @return boolean
     */
    public static function createMenu($menuJson, $accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $MENU_URL = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $accessToken;
        $info = NetHelper::request($MENU_URL, $menuJson);
        $result = json_decode($info, true);
        $result = $result["errcode"];
        // return $result;
        if ($result == 0) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * 获取菜单（JSON格式的数据）
     *
     * @param string $accessToken
     * @return mixed
     */
    public static function getMenu($accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $MENU_URL = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=" . $accessToken;

        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $MENU_URL);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        $menu_json = curl_exec($cu);
        $menu = json_decode($menu_json);
        curl_close($cu);

        return $menu;
    }

    /**
     * 删除菜单
     *
     * @param string $accessToken
     * @return bool 删除成功为true；删除失败为false
     */
    public static function deleteMenu($accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $MENU_URL = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=" . $accessToken;

        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $MENU_URL);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        $info = curl_exec($cu);
        $res = json_decode($info);
        curl_close($cu);

        if ($res->errcode == "0") {
            return true;
        } else {
            return false;
        }
    }

    public static function getOAuth2Code($appID = '')
    {
        //通过code获得openid
        if (!isset($_GET['code'])) {
            $redirectState = 1;
            //触发微信返回code码
            $redirectUrl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
            $url = self::getOAuth2PageUrl($redirectState, $redirectUrl, $appID);
            header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            return $code;
        }
    }

    /**
     * 获取微信的oauth2授权页面
     *
     * @param string $appID
     *            微信公众平台应用id
     * @param int $redirectState
     *            授权后跳转时携带的state参数
     * @param string $redirectUrl
     *            授权后待跳转的地址
     * @param string $scopeType
     *            应用授权作用域(默认值为snsapi_userinfo)，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）
     * @return string 拼接的授权地址
     */
    public static function getOAuth2PageUrl($redirectState, $redirectUrl, $appID = '', $scopeType = 'snsapi_userinfo')
    {
        if (empty($appID)) {
            $appID = WechatConfig::APPID;
        }

        $result = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appID&response_type=code&scope=$scopeType&state=$redirectState&redirect_uri=$redirectUrl#wechat_redirect";
        return $result;
    }

    /**
     * 获取oauth2认证后的用户访问accesstoken（其不同于非oauth2认证下的accesstoken）
     *
     * @param string $appID
     *            微信公众平台的应用标识
     * @param string $appSecret
     *            微信公众平台的密码
     * @param string $code
     *            oauth2认证的请求码，请求码是通过访问以下微信服务器地址时，生成并携带跳转到目标redirect_uri上
     *            https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
     * @param bool $useCache
     *            是否进行缓存
     * @param int $cacheSeconds
     *            缓存时间
     * @return mixed
     */
    public static function getOAuth2AccessToken($code, $appID = '', $appSecret = '', $useCache = true, $cacheSeconds = 3600)
    {
        if (empty($appID)) {
            $appID = WechatConfig::APPID;
        }

        if (empty($appSecret)) {
            $appSecret = WechatConfig::APPSECRET;
        }

        $basicinfo = self::getOAuth2UserBasicInfo($code, $appID, $appSecret, $useCache, $cacheSeconds);
        return $basicinfo['accesstoken'];
    }

    /**
     * 获取oauth2认证的基本信息
     *
     * @param string $appID
     *            微信公众平台的应用标识
     * @param string $appSecret
     *            微信公众平台的密码
     * @param string $code
     *            oauth2认证的请求码，请求码是通过访问以下微信服务器地址时，生成并携带跳转到目标redirect_uri上
     *            https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
     * @param bool $useCache
     *            是否进行缓存
     * @param int $cacheSeconds
     *            缓存时间
     * @return array 数组内包括元素accesstoken和openid
     */
    public static function getOAuth2UserBasicInfo($code, $appID = '', $appSecret = '', $useCache = true, $cacheSeconds = 3600)
    {
        $result = false;
        $cachekey = sprintf("oath2accesstoken20160108-appid:%s-secret:%s-code:%s", $appID, $appSecret, $code);
        if ($useCache == true) {
            $result = S($cachekey);
            if ($result != false && $result != "") {
                return $result;
            }
        }

        $url = sprintf("https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code", $appID, $appSecret, $code);
        $output = NetHelper::request($url);

        // $result = $output;
        // 检查错误、你可以加一段检查错误的语句（虽然这并不是必需的）
        if ($output === FALSE) {
            // 不解析返回的json信息
        } else {
            $output = json_decode($output, true);
            $result['accesstoken'] = $output["access_token"];
            $result['openid'] = $output['openid'];

            if ($useCache == true) {
                S($cachekey, $result, $cacheSeconds);
            }
        }

        return $result;
    }

    /**
     * 获取oauth2认证后的用户openid
     *
     * @param string $code
     *            oauth2认证的请求码，请求码是通过访问以下微信服务器地址时，生成并携带跳转到目标redirect_uri上
     *            https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
     * @param string $appID
     *            微信公众平台的应用标识
     * @param string $appSecret
     *            微信公众平台的密码
     * @param bool $useCache
     *            是否进行缓存
     * @param int $cacheSeconds
     *            缓存时间
     * @return mixed
     */
    public static function getOAuth2OpenID($code, $appID = '', $appSecret = '', $useCache = true, $cacheSeconds = 3600)
    {
        if (empty($appID)) {
            $appID = WechatConfig::APPID;
        }

        if (empty($appSecret)) {
            $appSecret = WechatConfig::APPSECRET;
        }

        $basicinfo = self::getOAuth2UserBasicInfo($code, $appID, $appSecret, $useCache, $cacheSeconds);
        return $basicinfo['openid'];
    }

    /**
     * 通过openid获取oauth2认证认证的微信用户的基本信息
     *
     * @param string $oauth2AccessToken
     *            oauth2认证后的用户访问accesstoken（其不同于非oauth2认证下的accesstoken）
     * @param string $openID
     *            oauth2认证后的用户openid
     * @return object 返回值格式为：
     *         object(stdClass)#14 (12) {
     *         ["subscribe"] => int(1)
     *         ["openid"] => string(28) "oOjPas9Yl4uOxEEPlhQhTmvPx7dI"
     *         ["nickname"] => string(6) "白雪"
     *         ["sex"] => int(2)
     *         ["language"] => string(5) "zh_CN"
     *         ["city"] => string(6) "海淀"
     *         ["province"] => string(6) "北京"
     *         ["country"] => string(6) "中国"
     *         ["headimgurl"] => string(118) "http://wx.qlogo.cn/mmopen/PiajxSqBRaELzYkFIlPNLmPUEHTiadPrH3SYY2FfT4BprLdTYJibiaF4tNUEaIwPcUM98mNcE86WHMDY0ZfXE6eazQ/0"
     *         ["subscribe_time"] => int(1451559474)
     *         ["remark"] => string(0) ""
     *         ["groupid"] => int(0)
     *         }
     */
    public static function getOAuth2UserInfo($openID, $oauth2AccessToken)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$oauth2AccessToken&openid=$openID";
        $output = NetHelper::request($url);
        $jsoninfo = json_decode($output);
        return $jsoninfo;
    }

    /**
     * 构造JSAPI签名数据包（分享给好友等功能时所用）
     *
     * @param string $appID
     * @param string $appSecret
     * @return array
     * @example 把构建好的值传递到页面，页面上如此使用
     *          <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
     *          <script>
     *          wx.config({
     *          appId: '{$signPackage.appId}',
     *          timestamp: {$signPackage.timestamp},
     *          nonceStr: '{$signPackage.nonceStr}',
     *          signature: '{$signPackage.signature}',
     *          jsApiList: [
     *          'checkJsApi',
     *          'onMenuShareTimeline',
     *          'onMenuShareAppMessage',
     *          'onMenuShareQQ',
     *          'onMenuShareWeibo'
     *          ]
     *          });
     *          wx.ready(function () {
     *          // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
     *          wx.checkJsApi({
     *          jsApiList: [
     *          'getNetworkType',
     *          'previewImage',
     *          'onMenuShareTimeline',
     *          'onMenuShareAppMessage',
     *          'onMenuShareQQ',
     *          'onMenuShareWeibo'
     *          ],
     *          });
     *
     *          var shareData = {
     *          //标题
     *          title: '我是解大然',
     *          //摘要
     *          desc: '这是一个很好游戏一起来玩吧。',
     *          //链接,可以换主页
     *          link: '{$signPackage.url}',
     *          //缩略图
     *          imgUrl: '缩略图',
     *
     *          };
     *          wx.onMenuShareAppMessage(shareData);
     *          wx.onMenuShareTimeline(shareData);
     *          wx.onMenuShareQQ(shareData);
     *          wx.onMenuShareWeibo(shareData);
     *          });
     *          </script>
     */
    public static function getJSAPISignPackage($appID = '', $appSecret = '')
    {
        if (empty($appID)) {
            $appID = WechatConfig::APPID;
        }

        if (empty($appSecret)) {
            $appSecret = WechatConfig::APPSECRET;
        }

        $accessToken = self::getAccessToken($appID, $appSecret);
        $jsapiTicket = self::getJsApiTicket($accessToken);
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timeStamp = time();
        $nonceString = RandHelper::rand(16);

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceString&timestamp=$timeStamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appID" => $appID,
            "nonceString" => $nonceString,
            "timeStamp" => $timeStamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    /**
     * 获取在使用JSAPI场景下所需要的票据
     *
     * @param string $accessToken
     * @return string
     */
    public static function getJsApiTicket($accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";

        $res = json_decode(NetHelper::request($url));
        //return $res;
        $ticket = $res->ticket;

        return $ticket;
    }

    /**
     * 发送文本型内容的客服消息
     *
     * @param string $toUserOpenID
     *            目标方微信用户openid
     * @param string $content
     *            文本内容
     * @param string $accessToken
     *            访问口令
     * @return bool|string 发送成功返回true，错误的时候则返回错误代码和错误信息拼接的字符串。
     */
    public static function responseCustomerServiceText($toUserOpenID, $content, $accessToken = '')
    {
        $data = '{
                    "touser":"' . $toUserOpenID . '",
                    "msgtype":"text",
                    "text":
                    {
                         "content":"' . $content . '"
                    }
                }';

        $result = self::responseCustomerService($data, $accessToken);
        return $result;
    }

    private static function responseCustomerService($data, $accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
        $result = NetHelper::request($url, $data);

        $result = json_decode($result);
        $errorCode = $result->errcode;
        $errorMessage = $result->errmsg;

        if ($errorCode == 0) {
            return true;
        } else {
            return '错误代码:[' . $errorCode . '].错误信息为:' . $errorMessage;
        }
    }

    /**
     * 发送文本型内容的客服消息
     *
     * @param string $toUserOpenID
     *            目标方微信用户openid
     * @param string $mediaID
     *            发送图片的id（此图片需要提前上传到微信服务器，获取的上传后的资源id）
     * @param string $accessToken
     *            访问口令
     * @return bool|string 发送成功返回true，错误的时候则返回错误代码和错误信息拼接的字符串。
     */
    public static function responseCustomerServiceImage($toUserOpenID, $mediaID, $accessToken = '')
    {
        $data = '{
                    "touser":"' . $toUserOpenID . '",
                    "msgtype":"image",
                    "image":
                    {
                         "media_id":"' . $mediaID . '"
                    }
                }';

        $result = self::responseCustomerService($data, $accessToken);
        return $result;
    }

    /**
     * 将长地址转换为短地址
     * @param $longUrl
     * @param string $accessToken
     * @return bool|string 成功返回转换后的短地址，失败返回false
     * @throws Common\WechatException
     */
    public static function shortenUrl($longUrl, $accessToken = '')
    {
        if (empty($accessToken)) {
            $accessToken = self::getAccessToken();
        }

        $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=$accessToken";
        $data = "{\"action\":\"long2short\",\"long_url\":\"$longUrl\"}";

        $out = NetHelper::request($url, $data);
        $result = json_decode($out, true);
        if ($result['errcode'] == 0) {
            return $result['short_url'];
        } else {
            return false;
        }
    }

    /**
     * 进行微信消息排重时，检测此微信消息是否需要处理
     * @param string $wxMessageRawData 微信服务器发送过来的原始数据
     * @return bool|number
     * 需要数据库支持，数据库的建表语句为
     *
     *
     * SET FOREIGN_KEY_CHECKS=0;
     * -- ----------------------------
     * -- Table structure for `multi_weixin_information`
     * -- ----------------------------
     * DROP TABLE IF EXISTS `multi_weixin_information`;
     * CREATE TABLE `multi_weixin_information` (
     * `id` int(11) NOT NULL AUTO_INCREMENT,
     * `msgid` varchar(50) DEFAULT NULL,
     * `openid` varchar(50) DEFAULT NULL,
     * `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
     * `remark` text,
     * PRIMARY KEY (`id`),
     * KEY `index_msgid` (`msgid`),
     * KEY `index_openid` (`openid`),
     * KEY `index_createtime` (`createtime`)
     * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
     */
    public static function checkNeedResponse($wxMessageRawData)
    {
        $receivedArray = (array)simplexml_load_string($wxMessageRawData, 'SimpleXMLElement', LIBXML_NOCDATA);

        $msgId = $receivedArray['MsgId'];
        $openId = $receivedArray['FromUserName'];
        $createTime = $receivedArray['CreateTime'];
        $rawData = $wxMessageRawData;

        //CommonLoger::log('wxneedResponse', "$msgId--$openId--$createTime");

        $mate = new ModelMate('weixinInformation');

        if ($msgId) {
            $dataGotten = $mate->find(array('msgid' => $msgId));
        } else {
            $dataGotten = $mate->find(array('openid' => $openId, 'createtime' => $createTime));
        }

        if ($dataGotten) {
            return false;
        } else {
            $data = array();
            $data['msgid'] = $msgId;
            $data['openid'] = $openId;
            $data['createtime'] = $createTime;
            $data['remark'] = $rawData;

            $result = $mate->interact($data);

            return $result;
        }
    }
}

?>