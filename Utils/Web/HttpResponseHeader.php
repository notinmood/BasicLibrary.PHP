<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/9
 * Time: 10:03
 */

namespace Hiland\Utils\Web;


use Hiland\Utils\Data\StringHelper;

/**
 *
 * Class HttpResponseHeader
 * @package Vendor\Hiland\Utils\Web
 */
class HttpResponseHeader
{
    public static function get($url, $key)
    {
        $key = strtoupper($key);
        $headers = self::getAll($url);
        foreach ($headers as $k => $v) {
            $k = strtoupper($k);
            if ($k == $key) {
                return $v;
            }
        }

        return false;
    }

    public static function getAll($url)
    {
        $cacheKey = "HttpResponseHeader20160709-url-$url";
        $dataCached = S($cacheKey);
        if (!empty($dataCached)) {
            return $dataCached;
        }

        $data = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);  //输出header信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  //不显示网页内容
        curl_setopt($curl, CURLOPT_ENCODING, ''); //允许执行gzip
        $html = curl_exec($curl);
        if (!curl_errno($curl)) {
            $info = curl_getinfo($curl);
            $httpHeaderSize = $info['header_size'];  //header字符串体积
            $pHeader = substr($html, 0, $httpHeaderSize); //获得header字符串
            $split = array("\r\n", "\n", "\r");  //需要格式化header字符串
            $pHeader = str_replace($split, '<br/>', $pHeader); //使用<br>换行符格式化输出到网页上

            $tempArray = explode("<br/>", $pHeader);
            foreach ($tempArray as $item) {
                $key = StringHelper::getStringBeforeSeperator($item, ":");
                $value = StringHelper::getStringAfterSeperator($item, ":");
                $data[$key] = trim($value);
            }
        }

        S($cacheKey, $data);

        return $data;
    }
}