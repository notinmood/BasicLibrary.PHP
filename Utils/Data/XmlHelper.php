<?php

namespace Hiland\Utils\Data;

class XmlHelper
{
    /**
     * 将 xml 转换成 json
     * @param string $xmlData         待转换的 xml 其可以是一个 xml 文件地址，也可以是一个 xml 原始字符串
     * @param bool   $escapeToUnicode 是否将中文等信息进行 unicode 转码（缺省 true，转码）
     * @return string
     */
    public static function convertToJson(string $xmlData, bool $escapeToUnicode = true): string
    {
        // 传的是文件，还是xml的string的判断
        if (is_file($xmlData)) {
            $xml_array = simplexml_load_file($xmlData);
        } else {
            $xml_array = simplexml_load_string($xmlData);
        }

        // php5，以及以上，如果是更早版本，请查看JSON.php
        if ($escapeToUnicode) {
            $json = json_encode($xml_array);
        } else {
            $json = json_encode($xml_array, JSON_UNESCAPED_UNICODE);
        }

        return $json;
    }

    /**
     * 将 json 转换成 xml
     * @param string $jsonString
     * @param string $charset
     * @return boolean|string
     */
    public static function convertFromJson(string $jsonString, string $charset = 'utf8')
    {
        if (empty($jsonString)) {
            return false;
        }

        $array = json_decode($jsonString); // php5以及以上;如果是更早版本，請下載JSON.php
        return ArrayHelper::convertToXml($array, 'myXml', true, $charset);
    }
}