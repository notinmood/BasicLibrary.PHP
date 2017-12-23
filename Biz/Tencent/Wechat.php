<?php
namespace Hiland\Biz\Tencent;

use Hiland\Biz\Tencent\Common\WechatConfig;

/**
 * 微信公众平台 PHP SDK
 *
 * @author NetPuter <netputer@gmail.com>
 */

/**
 * 微信公众平台处理类
 * 使用时要对此类继承扩展出新类型，覆盖里面的on****等方法（进行业务实现）
 */
class Wechat
{
    /**
     * 从微信服务器发送过来的原始的请求数据
     *
     * @var string
     */
    protected $originalRequestData;
    /**
     * 调试模式，将错误通过文本消息回复显示
     *
     * @var boolean
     */
    private $debug;
    /**
     * 以数组的形式保存微信服务器每次发来的请求
     *
     * @var array
     */
    private $request;

    /**
     * 初始化，判断此次请求是否为验证请求，并以数组形式保存
     *
     * @param string $token
     *            验证信息
     * @param boolean $debug
     *            调试模式，默认为关闭
     */
    public function __construct($token = '', $debug = FALSE)
    {
        if (empty($token)) {
            $token = WechatConfig::GATETOKEN;
        }

        if ($this->isValid() && $this->validateSignature($token)) {
            exit($_GET['echostr']);
        }

        $this->debug = $debug;
        set_error_handler(array(
            &$this,
            'errorHandler'
        ));
        // 设置错误处理函数，将错误通过文本消息回复显示

        $this->originalRequestData = file_get_contents("php://input");//$GLOBALS['HTTP_RAW_POST_DATA'];
        $xml = (array)simplexml_load_string($this->originalRequestData, 'SimpleXMLElement', LIBXML_NOCDATA);

        $this->request = array_change_key_case($xml, CASE_LOWER);
        // 将数组键名转换为小写，提高健壮性，减少因大小写不同而出现的问题
    }

    /**
     * 判断此次请求是否为验证请求
     *
     * @return boolean
     */
    private function isValid()
    {
        return isset($_GET['echostr']);
    }

    /**
     * 判断验证请求的签名信息是否正确
     *
     * @param string $token
     *            验证信息
     * @return boolean
     */
    private function validateSignature($token)
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];

        $signatureArray = array(
            $token,
            $timestamp,
            $nonce
        );
        sort($signatureArray, SORT_STRING);

        return sha1(implode($signatureArray)) == $signature;
    }

    /**
     * 获取当前跟公众平台交互用户的Openid
     */
    public function getRequestOpenid()
    {
        return $this->getRequest('fromusername');
    }

    /**
     * 获取本次请求中的参数，不区分大小
     *
     * @param bool $param
     *            参数名，默认为无参
     * @return mixed
     */
    protected function getRequest($param = FALSE)
    {
        if ($param === FALSE) {
            return $this->request;
        }

        $param = strtolower($param);

        if (isset($this->request[$param])) {
            return $this->request[$param];
        }

        return NULL;
    }

    /**
     * 分析消息类型，并分发给对应的函数
     *
     * @return void
     */
    public function run()
    {
        switch ($this->getRequest('msgtype')) {

            case 'event':
                switch (strtolower($this->getRequest('event'))) {
                    case 'subscribe':
                        $this->onSubscribe();
                        break;
                    case 'unsubscribe':
                        $this->onUnsubscribe();
                        break;
                    case 'click':
                        $this->onClick();
                        break;
                    case 'scan':
                        $this->onScan();
                        break;
                }

                break;

            case 'text':
                $this->onText();
                break;

            case 'image':
                $this->onImage();
                break;

            case 'location':
                $this->onLocation();
                break;

            case 'link':
                $this->onLink();
                break;

            default:
                $this->onUnknown();
                break;
        }
    }

    /**
     * 用户关注时触发，用于子类重写
     *
     * @return void
     */
    protected function onSubscribe()
    {
    }

    /**
     * 用户取消关注时触发，用于子类重写
     *
     * @return void
     */
    protected function onUnsubscribe()
    {
    }

    /**
     * 收到菜单的点击事件
     */
    protected function onClick()
    {
    }

    /**
     * 收到扫描二维码的事件（用户扫描公众平台默认的二维码不会触发本事件）
     * （只有已经是微信公众平台用户了，扫描二维码的时候才会触发本事件；否则即便扫描二维码也是触发的为订阅事件onSubscribe）
     */
    protected function onScan()
    {
    }

    /**
     * 收到文本消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onText()
    {
    }

    /**
     * 收到图片消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onImage()
    {
    }

    /**
     * 收到地理位置消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onLocation()
    {
    }

    /**
     * 收到链接消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onLink()
    {
    }

    /**
     * 收到未知类型消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onUnknown()
    {
    }

    /**
     * 自定义的错误处理函数，将 PHP 错误通过文本消息回复显示
     *
     * @param int $level
     *            错误代码
     * @param string $msg
     *            错误内容
     * @param string $file
     *            产生错误的文件
     * @param int $line
     *            产生错误的行数
     * @return void
     */
    public function errorHandler($level, $msg, $file, $line)
    {
        if (!$this->debug) {
            return;
        }

        $error_type = array(
            // E_ERROR => 'Error',
            E_WARNING => 'Warning',
            // E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            // E_CORE_ERROR => 'Core Error',
            // E_CORE_WARNING => 'Core Warning',
            // E_COMPILE_ERROR => 'Compile Error',
            // E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated'
        );

        $template = <<<ERR
PHP 报错啦！

%s: %s
File: %s
Line: %s
ERR;

        $this->responseText(sprintf($template, $error_type[$level], $msg, $file, $line));
    }

    /**
     * 回复文本消息
     *
     * @param string $content
     *            消息内容
     * @param int $funcFlag
     *            默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseText($content, $funcFlag = 0)
    {
        exit(new TextResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $content, $funcFlag));
    }

    /**
     * 回复图片消息
     *
     * @param int $mediaid
     *            已经上传到微信服务器上的图片id
     * @param int $funcFlag
     */
    protected function responseImage($mediaid, $funcFlag = 0)
    {
        exit(new ImageResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $mediaid, $funcFlag));
    }

    /**
     * 回复音乐消息
     *
     * @param string $title
     *            音乐标题
     * @param string $description
     *            音乐描述
     * @param string $musicUrl
     *            音乐链接
     * @param string $hqMusicUrl
     *            高质量音乐链接，Wi-Fi 环境下优先使用
     * @param integer $funcFlag
     *            默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseMusic($title, $description, $musicUrl, $hqMusicUrl, $funcFlag = 0)
    {
        exit(new MusicResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $title, $description, $musicUrl, $hqMusicUrl, $funcFlag));
    }

    /**
     * 回复图文消息
     *
     * @param array $items
     *            由单条图文消息类型 NewsResponseItem() 组成的数组
     * @param integer $funcFlag
     *            默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseNews($items, $funcFlag = 0)
    {
        exit(new NewsResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $items, $funcFlag));
    }
}

/**
 * 用于回复的基本消息类型
 */
abstract class WechatResponse
{

    protected $toUserName;

    protected $fromUserName;

    protected $funcFlag;

    public function __construct($toUserName, $fromUserName, $funcFlag)
    {
        $this->toUserName = $toUserName;
        $this->fromUserName = $fromUserName;
        $this->funcFlag = $funcFlag;
    }

    abstract public function __toString();
}

/**
 * 用于回复的文本消息类型
 */
class TextResponse extends WechatResponse
{

    protected $content;

    protected $template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;

    public function __construct($toUserName, $fromUserName, $content, $funcFlag = 0)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->content = $content;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), $this->content, $this->funcFlag);
    }
}

/**
 * 用于回复的音乐消息类型
 */
class MusicResponse extends WechatResponse
{

    protected $title;

    protected $description;

    protected $musicUrl;

    protected $hqMusicUrl;

    protected $template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[music]]></MsgType>
  <Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
  </Music>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;

    public function __construct($toUserName, $fromUserName, $title, $description, $musicUrl, $hqMusicUrl, $funcFlag)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->title = $title;
        $this->description = $description;
        $this->musicUrl = $musicUrl;
        $this->hqMusicUrl = $hqMusicUrl;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), $this->title, $this->description, $this->musicUrl, $this->hqMusicUrl, $this->funcFlag);
    }
}

/**
 * 用于回复的图文消息类型
 */
class NewsResponse extends WechatResponse
{

    protected $items = array();

    protected $template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[news]]></MsgType>
  <ArticleCount>%s</ArticleCount>
  <Articles>
    %s
  </Articles>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;

    public function __construct($toUserName, $fromUserName, $items, $funcFlag)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->items = $items;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), count($this->items), implode($this->items), $this->funcFlag);
    }
}

/**
 * 单条图文消息类型
 */
class NewsResponseItem
{

    protected $title;

    protected $description;

    protected $picUrl;

    protected $url;

    protected $template = <<<XML
<item>
  <Title><![CDATA[%s]]></Title>
  <Description><![CDATA[%s]]></Description>
  <PicUrl><![CDATA[%s]]></PicUrl>
  <Url><![CDATA[%s]]></Url>
</item>
XML;

    public function __construct($title, $description, $picUrl, $url)
    {
        $this->title = $title;
        $this->description = $description;
        $this->picUrl = $picUrl;
        $this->url = $url;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->title, $this->description, $this->picUrl, $this->url);
    }
}

/**
 * 用于回复的图片消息类型
 */
class ImageResponse extends WechatResponse
{

    protected $mediaId;

    protected $template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[image]]></MsgType>
  <Image>
    <MediaId><![CDATA[%s]]></MediaId>
  </Image>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;

    public function __construct($toUserName, $fromUserName, $mediaId, $funcFlag = 0)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->mediaId = $mediaId;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), $this->mediaId, $this->funcFlag);
    }
}