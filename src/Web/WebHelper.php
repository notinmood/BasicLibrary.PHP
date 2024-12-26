<?php

namespace Hiland\Web;

use Hiland\Biz\ThinkAddon\TPCompatibleHelper;
use Hiland\Data\ObjectHelper;
use Hiland\Data\StringHelper;
use Hiland\IO\FileHelper;
use JetBrains\PhpStorm\NoReturn;

/**
 * @TODO   本类需要重构,将具体的实现方法分别分布到其他类型 helper中,
 * 本类仅仅提供以上功能友好的对外接口(即其他类型功能的别名)
 * @author 山东解大劦
 */
class WebHelper
{
    /**
     * 下载文件
     * 说明 Controller的Action方法中，调用本方法后不能再出现 dump(); display();这样的向浏览器页面刷信息的方法。
     * @param mixed $data 可以是带全路径的文件名称，也可以数组，字符串或者内存数据流
     * @param string|null $newFileName 在客户浏览器弹出下载对话框中显示的默认文件名
     */
    public static function download($data, string $newFileName = null)
    {
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Pragma:public");

        if (is_file($data)) {
            if (empty($newFileName)) {
                $newFileName = FileHelper::getBaseName($data);
            }

            header('Content-Type:' . MimeHelper::getMime($data));
            header('Content-Disposition: attachment; filename=' . $newFileName);
            header('Content-Length:' . filesize($data));

            readfile($data);
        } else {
            if (empty($newFileName)) {
                $newFileName = date("YmdHis");
            }

            header("Content-type:application/octet-stream");
            header("Accept-Ranges:bytes");
            header("Content-Disposition:attachment;filename=$newFileName");

            file_put_contents("php://output", $data);
        }

        /**
         * 结束输出流
         */
        exit();
    }

    /**
     * 从一个url中提取所有 meta 标签的 content 属性
     * @param        $url
     * @param string $tagName 网页上的meta的tag名称,缺省为空的时候返回所有tag的内容
     * @return array|false|mixed
     */
    public static function getWebMetas($url, string $tagName = ""): mixed
    {
        $result = get_meta_tags($url);
        if ($tagName) {
            return ObjectHelper::getMember($result, $tagName, "");
        } else {
            return $result;
        }
    }

    /**
     * 网页跳转
     * @param string $targetUrl 待跳转的页面
     */
    public static function redirectUrl(string $targetUrl): void
    {
        header('location:' . $targetUrl);
    }

    /**
     * 给url附加参数信息
     * @param string $url 原url
     * @param array|string $paraData 将要作为url参数被附加在url后面，的带名值对类型的数组或者已经排列好的参数名值对字符串
     * @param bool $isUrlEncode 是否对参数的值进行url编码
     * @return string 附加了参数信息的url
     */
    public static function attachUrlParameter(string $url, $paraData, bool $isUrlEncode = false): string
    {
        //$paraString = '';
        if (is_string($paraData)) {
            $paraString = $paraData;
        } else {
            $paraString = self::convertArrayToUrlParameter($paraData, $isUrlEncode);
        }

        if (StringHelper::isContains($url, "?")) {
            $url .= "&$paraString";
        } else {
            $url .= "?$paraString";
        }
        return $url;
    }

    /**
     * 对一个名值对数组格式化为url的参数
     * @param array $paraArray 需要格式化的名值对数组
     * @param bool $isUrlEncode 是否对参数的值进行url编码(暂时不可使用)
     * @param array|null $excludeParaArray 不编制在url参数列表中的参数名数组（只有参数名称的一维数组）
     * @param bool $isSortPara 是否对参数进行排序
     * @return string
     */
    public static function convertArrayToUrlParameter(array $paraArray, bool $isUrlEncode = false, array $excludeParaArray = null, bool $isSortPara = true): string
    {
        if ($isSortPara) {
            ksort($paraArray);
        }

        if ($excludeParaArray) {
            $paraArray = array_diff_key($paraArray, array_flip($excludeParaArray));
        }

        return static::buildQueryString($paraArray);
    }

    /**
     * 构建一个url参数查询字符串（将http_build_query的功能分组到类型内部，为了记忆方便。）
     * @param array|object $data
     * @return string
     */
    public static function buildQueryString(array|object $data): string
    {
        return http_build_query($data);
    }

    /**
     * 服务器端返回JSONP类型数据
     * @param mixed $data 发送到客户浏览器的数据
     * @param string $callbackClientFuncName 回调的用户浏览器的函数名称
     * @param int $jsonOption 传递给json_encode的option参数(为避免中文转码请使用JSON_UNESCAPED_UNICODE)
     */
    #[NoReturn] public static function jsonp(mixed $data, string $callbackClientFuncName = "", int $jsonOption = 0): void
    {
        self::serverReturn($data, "JSONP", $jsonOption, $callbackClientFuncName);
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param string $type AJAX返回数据格式,默认值为JSON
     * @param int $jsonOption 传递给json_encode的option参数(为避免中文转码请使用JSON_UNESCAPED_UNICODE)
     * @param string $callbackClientFuncName 如果是jsonp的时候，此处为回调函数的名称(或者为回调函数名称的形参名称)
     * @return void
     */
    #[NoReturn] public static function serverReturn(mixed $data, string $type = '', int $jsonOption = 0, string $callbackClientFuncName = ""): void
    {
        if (empty($type)) {
            $type = 'JSON';
        }

        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $data = json_encode($data, $jsonOption);
                break;
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');

                /**
                 * 1、先判断是否通过参数传递过来回调函数的信息
                 */
                if ($callbackClientFuncName) {
                    $handler = $_GET[$callbackClientFuncName];

                    if (!$handler) {
                        $handler = $callbackClientFuncName;
                    }
                } else {
                    /**
                     * 2、接着从配置文件内读取浏览器传递过来的 需要回调的JavaScript函数名称
                     */
                    $handler = isset($_GET[TPCompatibleHelper::config('VAR_JSONP_HANDLER')]) ? $_GET[TPCompatibleHelper::config('VAR_JSONP_HANDLER')] : TPCompatibleHelper::config('DEFAULT_JSONP_HANDLER');
                }

                if (!$handler) {
                    $handler = "callback";
                }

                $data = $handler . '(' . json_encode($data, $jsonOption) . ');';
                break;
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                $data = 'eval(' . json_encode($data, $jsonOption) . ');';
                break;
        }
        exit ($data);
    }

    /**
     * 获取网站的域名信息
     * 不包括前面的"http://"和后面的"/"
     * @return string
     */
    public static function getHostName(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? '');
    }


    /**
     * 获取给定url的内容
     * @param string $url
     * @param bool $isUseZlib 是否使用zlib压缩(默认使用zlib,即便是未经过压缩的网页使用本设置也不会出错)
     * @return string
     */
    public static function getContent(string $url, bool $isUseZlib = true): string
    {
        $compressType = "";
        if ($isUseZlib) {
            $compressType = "compress.zlib://";
        }

        return file_get_contents($compressType . $url);
    }
}
