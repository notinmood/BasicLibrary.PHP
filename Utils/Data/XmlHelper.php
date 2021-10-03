<?php

namespace Hiland\Utils\Data;

class XmlHelper
{
    /**
     * 将xml转换成json
     * @param string $xmlData
     *            待转换的xml 其可以是一个xml文件地址，也可以是一个xml原始字符串
     * @param bool $escapeToUnicode 是否将中文等信息进行unicode转码（缺省true，转码）
     * @return string
     */
    public static function convertToJson($xmlData, $escapeToUnicode = true)
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
     * 将json转换成xml
     * @param string $jsonData
     * @param string $charset
     * @return boolean|string
     */
    public static function convertFromJson($jsonData, $charset = 'utf8')
    {
        if (empty($jsonData)) {
            return false;
        }

        $array = json_decode($jsonData); // php5以及以上;如果是更早版本，請下載JSON.php
        return ArrayHelper::convertToXml($array, 'myxml', true, $charset);
    }
}