<?php

namespace Hiland\Web;


use Hiland\Data\ThinkHelper;
use Hiland\Environment\EnvHelper;
use think\Container;
use think\Request;

/**
 * 请求辅助类
 */
class RequestHelper
{
    /**
     * 判断当前是否为post请求
     * @return int|null
     */
    public static function isPost(): ?int
    {
        $requestEntity = self::getRequestEntity();
        if ($requestEntity) {
            return $requestEntity->isPost();
        } else {
            return ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
        }
    }

    /**
     * 判断当前是否为get请求
     * @return bool|null
     */
    public static function isGet(): ?bool
    {
        $requestEntity = self::getRequestEntity();
        if ($requestEntity) {
            return $requestEntity->isGet();
        } else {
            return $_SERVER['REQUEST_METHOD'] == 'GET';
        }
    }

    /**
     * 从请求内获取数据
     * @param string $name 数据的名称
     * @param mixed|null $default 数据的缺省值
     * @param string|RequestMethods $method 请求的方式(get、post等)
     * @return mixed
     */
    public static function getInput(string $name, mixed $default = null, string|RequestMethods $method = RequestMethods::ALL): mixed
    {
        if (ThinkHelper::isThinkPHP() && function_exists("input")) {
            return input($name, $default);
        } else {
            switch ($method) {
                case RequestMethods::GET:
                    $result = $_GET[$name];
                    break;
                case RequestMethods::POST:
                    $result = $_POST[$name];
                    break;
                case RequestMethods::COOKIE:
                    $result = $_COOKIE[$name];
                    break;
                case RequestMethods::SESSION:
                    $result = $_SESSION[$name];
                    break;
                case RequestMethods::SERVER:
                    $result = $_SERVER[$name];
                    break;
                default:
                    $result = $_GET[$name];
                    if ($result === null) {
                        $result = $_POST[$name];
                    }

                    if ($result === null) {
                        $result = $_POST[$name];
                    }

                    if ($result === null) {
                        $result = $_COOKIE[$name];
                    }

                    if ($result === null) {
                        $result = $_SESSION[$name];
                    }

                    if ($result === null) {
                        $result = $_SERVER[$name];
                    }
            }

            return $result ?? $default;
        }
    }

    /**
     * 获取当前请求的原始输入数据（与getPhpInput（）互为别名）
     * @return string
     */
    public static function getInputAll(): string
    {
        return file_get_contents("php://input");
    }

    /**
     * 获取当前请求的原始输入数据（与getInputAll（）互为别名）
     * @return string
     */
    public static function getPhpInput(): string
    {
        return file_get_contents("php://input");
    }

    /**
     * @return mixed
     */
    private static function getRequestEntity(): mixed
    {
        if (EnvHelper::isThinkPHP()) {
            if (class_exists("think\Container")) {
                $requestEntity = Container::get("request");
            } else {
                $requestEntity = Request::instance();
            }
            return $requestEntity;
        } else {
            return null;
        }
    }

    /**
     * 获取当前请求的全路径
     * @return string
     */
    public static function getFullPath(): string
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $hostName  = WebHelper::getHostName();
        return $http_type . $hostName . $_SERVER['REQUEST_URI'];
    }

    /**
     * 模拟网络POST请求
     * @param string $url
     * @param mixed|null $data
     * @param null $optionalHeaders
     * @return string
     * @throws Exception
     */
    public static function post(string $url, mixed $data = null, $optionalHeaders = null): string
    {
        try {
            return HttpClientHelper::post($url, $data, $optionalHeaders);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 模拟网络Get请求
     * @param string $url
     * @param bool $isCloseAtOnce 是否立即关闭连接
     * @return string
     */
    public static function get(string $url, bool $isCloseAtOnce = false): string
    {
        return HttpClientHelper::get($url, $isCloseAtOnce);
    }

    /**
     * 根据是否有$data值进行智能判断是发起post还是get请求
     * @param string $url
     *            被请求的url
     * @param mixed|null $data
     *            post请求时发送的数据
     * @param int $timeOutSeconds
     *            请求超时时间
     * @param bool $isSSLVerify
     *            是否进行ssl验证
     * @param array $headerArray
     *            请求头信息
     * @param bool $isForceUnSafe
     *            是否强制启用非安全模式（php5.6下在向微信服务器上传资源的时候选用此选项）
     * @param array $certificateFileArray
     *            请求的证书信息（证书需要带全部的物理路径）并且证书的文件名命名格式要求如下：
     *            cert证书 命名格式为 *****cert.pem
     *            key证书命名格式为 *****key.pem
     *            ca证书命名格式为 *****ca.pem
     * @return bool|string
     * @throws Exception
     */
    public static function request(string $url, mixed $data = null, int $timeOutSeconds = 0, bool $isSSLVerify = false,
                                   array  $headerArray = array(), array $certificateFileArray = array(),
                                   bool   $isForceUnSafe = false): bool|string
    {
        try {
            return HttpClientHelper::request($url, $data, $timeOutSeconds, $isSSLVerify, $headerArray, $certificateFileArray, $isForceUnSafe);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
