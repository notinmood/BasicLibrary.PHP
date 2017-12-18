<?php

namespace Hiland\Utils\Data;

class XmlHelper
{
    /**
     * 将xml转换成json
     *
     * @param string $xml
     *            待转换的xml 其可以是一个xml文件地址，也可以是一个xml原始字符串
     * @param bool $escapeToUnicode 是否将中文等信息进行unicode转码（缺省true，转码）
     * @return string
     */
    public static function toJson($xml, $escapeToUnicode = true)
    {
        // 传的是文件，还是xml的string的判断
        if (is_file($xml)) {
            $xml_array = simplexml_load_file($xml);
        } else {
            $xml_array = simplexml_load_string($xml);
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
     *
     * @param string $json
     * @param string $charset
     * @return boolean|string
     */
    public static function toXml($json, $charset = 'utf8')
    {
        if (empty($json)) {
            return false;
        }

        $array = json_decode($json); // php5，以及以上，如果是更早版本，請下載JSON.php
        $xml = ArrayHelper::Toxml($array, 'myxml', true, $charset);
        return $xml;
    }
}