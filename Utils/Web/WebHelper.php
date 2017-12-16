<?php

namespace Hiland\Utils\Web;

use Hiland\Utils\Data\StringHelper;

/**
 *
 * @author 然
 */
class WebHelper
{
    /**
     * 下载文件
     * 说明 Controller的Action方法中，调用本方法后不能再出现 dump(); display();这样的向浏览器页面刷信息的方法。
     * @param mixed $data 可以是带全路径的文件名称，也可以数组，字符串或者内存数据流
     * @param string $newFileName 在客户浏览器弹出下载对话框中显示的默认文件名
     */
    public static function download($data, $newFileName = null)
    {
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Pragma:public");

        if (is_file($data)) {
            if (empty($newFileName)) {
                $newFileName = $data;
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
    }

    /**
     * 网页跳转
     *
     * @param string $targetUrl
     *            待跳转的页面
     */
    public static function redirectUrl($targetUrl)
    {
        header('location:' . $targetUrl);
    }

    /**
     * 给url附加参数信息
     *
     * @param string $url
     *            原url
     * @param array|string $paraData
     *            将要作为url参数被附加在url后面，的带名值对类型的数组或者已经排列好的参数名值对字符串
     * @param bool $isUrlEncode
     *            是否对参数的值进行url编码
     * @return string 附加了参数信息的url
     */
    public static function attachUrlParameter($url, $paraData, $isUrlEncode = false)
    {
        //$paraString = '';
        if (is_string($paraData)) {
            $paraString = $paraData;
        } else {
            $paraString = self::formatArrayAsUrlParameter($paraData, $isUrlEncode);
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
     *
     * @param array $paraArray
     *            需要格式化的名值对数组
     * @param bool $isUrlEncode
     *            是否对参数的值进行url编码
     * @param array $excludeParaArray
     *            不编制在url参数列表中的参数名数组（只有参数名称的一维数组）
     * @param bool $isSortPara 是否对参数进行排序
     * @return string
     */
    public static function formatArrayAsUrlParameter($paraArray, $isUrlEncode = false, $excludeParaArray = null, $isSortPara = true)
    {
        $buffString = "";

        if ($isSortPara) {
            ksort($paraArray);
        }

        foreach ($paraArray as $k => $v) {
            if (in_array($k, $excludeParaArray)) {
                continue;
            }

            if (empty($v)) {
                $v = '';
            }

            if ($isUrlEncode) {
                $v = urlencode($v);
            }

            $buffString .= $k . "=" . $v . "&";
        }
        $result = '';
        if (strlen($buffString) > 0) {
            $result = substr($buffString, 0, strlen($buffString) - 1);
        }
        return $result;
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式,默认值为JSON
     * @param int $json_option 传递给json_encode的option参数(为避免中午转码请使用JSON_UNESCAPED_UNICODE)
     * @return void
     */
    public static function serverReturn($data, $type = '', $json_option = 0)
    {
        if (empty($type)) {
            $type = 'JSON';
        }

        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $data = json_encode($data, $json_option);
                break;
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                $data = $handler . '(' . json_encode($data, $json_option) . ');';
                break;
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                break;
        }
        exit ($data);
    }
//
//    public static function getWebPhysicalRootPath()
//    {
//        return dirname('/');
//        //return $_SERVER['DOCUMENT_ROOT'];
//    }

    /**
     * 获取应用程序地址
     * @param string $schema
     * @return string
     */
    public static function getWebAppFull($schema = "http://")
    {
        return $schema . self::getHostName() . self::getWebApp();
    }

    /**
     * 获取网站的域名信息
     * 不包括前面的"http://"和后面的"/"
     *
     * @return string
     */
    public static function getHostName()
    {
        return EnvironmentHelper::getServerHostName();
    }

    /**
     * 获取应用程序地址
     * @return string
     */
    public static function getWebApp()
    {
        return (__APP__);
    }

    /**
     * 获取应用程序入口页面地址（在模式下，比getWebApp少一个问号）
     * @param string $schema
     * @return string
     */
    public static function getWebGateFull($schema = "http://")
    {
        return $schema . self::getHostName() . self::getWebGate();
    }

    /**
     * 获取应用程序入口页面地址（在模式下，比getWebApp少一个问号）
     * @return string
     */
    public static function getWebGate()
    {
        return _PHP_FILE_;
    }

    /**
     * 获取网站的域名信息
     * 不包括后面的"/"
     * @param string $schema
     * @return string
     */
    public static function getHostNameFull($schema = "http://")
    {
        return $schema . self::getHostName();
    }

    /**
     * 获取全路径的应用程序的根
     * @param string $schema
     * @return string
     */
    public static function getWebRootFull($schema = "http://")
    {
        return $schema . self::getHostName() . self::getWebRoot();
    }

    /**
     * 获取应用程序的根
     * @return string
     */
    public static function getWebRoot()
    {
        return __ROOT__;
    }
}

?>