<?php

namespace Hiland\Web;

use Exception;
use Hiland\Data\StringHelper;
use Hiland\IO\FileHelper;

/**
 * 无论是get还是post请求都推荐使用request方法，因为request方法更靠近底层，可以更精确的控制请求信息。
 */
class HttpClientHelper
{
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
        $params = array(
            'http' => array(
                'method' => 'POST',
                'content' => $data,
            ),
        );
        if ($optionalHeaders !== null) {
            $params['http']['header'] = $optionalHeaders;
        }
        $ctx = stream_context_create($params);
        $fp  = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new RuntimeException("Problem with $url");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new RuntimeException("Problem reading data from $url");
        }
        return $response;
    }

    /**
     * 模拟网络Get请求
     * @param string $url
     * @param bool $isCloseAtOnce 是否立即关闭连接
     * @return string
     */
    public static function get(string $url, bool $isCloseAtOnce = false): string
    {
        if ($isCloseAtOnce) {
            $context = stream_context_create(array('http' => array('header' => 'Connection: close\r\n')));
            $result  = file_get_contents($url, false, $context);
        } else {
            $result = file_get_contents($url);
        }

        return $result;
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
        if ($timeOutSeconds === 0) {
            $timeOutSeconds = 30;
        }
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_TIMEOUT, $timeOutSeconds);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        //因为php版本的原因，上传素材一直报错。php的curl的curl_setopt 函数存在版本差异
        //PHP5.5已经把通过@加文件路径上传文件的方式给放入到Deprecated中了。php5.6默认是不支持这种方式了
        if ($isForceUnSafe === true) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        if ($headerArray && count($headerArray) >= 1) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        }

        if ($isSSLVerify) {
            // 检测服务器的证书是否由正规浏览器认证过的授权CA颁发的
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
            // 检测服务器的域名与证书上的是否一致
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 严格校验
        } else {
            /** @noinspection all */
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //不需要验证主机
            /** @noinspection all */
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); //不需要证书验证
        }

        if ($certificateFileArray) {
            foreach ($certificateFileArray as $key) {
                $fileBaseName = FileHelper::getBaseName($key);
                if (StringHelper::isContains($fileBaseName, "cert.pem")) {
                    curl_setopt($curl, CURLOPT_SSLCERT, $key);
                }

                if (StringHelper::isContains($fileBaseName, "key.pem")) {
                    curl_setopt($curl, CURLOPT_SSLKEY, $key);
                }

                if (StringHelper::isContains($fileBaseName, "ca.pem")) {
                    curl_setopt($curl, CURLOPT_CAINFO, $key);
                }
            }
        }

        $output = curl_exec($curl);

        // 返回结果
        if ($output) {
            curl_close($curl);
            return $output;
        }

        $error = curl_errno($curl);
        curl_close($curl);
        throw new RuntimeException("curl出错，错误码:$error");
    }
}
