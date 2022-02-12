<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2016/11/1
 * Time: 8:26
 */

namespace Hiland\Utils\Data;

/**
 * 可以参考base64原理 http://www.cnblogs.com/diligenceday/p/6002382.html
 * Class Base64Helper
 * @package Vendor\Hiland\Utils\Data
 */
class Base64Helper
{
    /**
     * 进行 base64 编码
     * @param string $originalString 待编码的原始字符串
     * @param false  $safely 是否要进行安全转换(为了安全起见,(在url等场景中)需要将这两个字符 + / （加除，加粗）进行合理地替换)
     * @return array|string|string[]/
     */
    public static function encode(string $originalString, bool $safely = false)
    {
        $result = base64_encode($originalString);

        if ($safely === true) {
            $result = self::getSafeBase64($result);
        }

        return $result;
    }

    /**
     * 进行 base64 解码
     * @param string $base64String
     * @return false|string
     */
    public static function decode(string $base64String)
    {
        $base64String = self::getOriginalBase64($base64String);
        return base64_decode($base64String);
    }

    /**
     * 判断字符串是否进行了base64加密
     * @param string $stringData
     * @return bool
     */
    public static function isBase64(string $stringData): bool
    {
        $length = StringHelper::getLength($stringData);
        if ($length % 4 !== 0) {
            return false;
        }

        for ($i = 0; $i < $length; $i++) {
            $currentChar = $stringData[$i];
            if ($currentChar >= 'A' && $currentChar <= 'Z') {
                continue;
            }
            if ($currentChar >= 'a' && $currentChar <= 'z') {
                continue;
            }
            if ($currentChar >= '0' && $currentChar <= '9') {
                continue;
            }
            if ($currentChar === '+' || $currentChar === '\\' || $currentChar === '=') {
                continue;
            }

            return false;
        }
        return true;
    }

    /**获取安全的base64编码（用于在url中传递等工作）
     * Base64编码中使用了 两个特殊字符 + / （加除，加粗）    可以谐音为 “贝斯是加粗”的吉他
     * 为了安全起见,(在url等场景中)需要将这两个字符进行合理的替换
     * @param $commonBase64String
     * @return array|string|string[]
     */
    private static function getSafeBase64($commonBase64String)
    {
        $string = str_replace(array('+', '/', '='), array('_', '|', '-'), $commonBase64String);
        $mod4 = strlen($string) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }

        return $string;
    }

    /**
     * 从安全的base64编码，还原原始的base64编码
     * @param $safeBase64String
     * @return array|string|string[]
     */
    private static function getOriginalBase64($safeBase64String)
    {
        return str_replace(array('_', '|', '-'), array('+', '/', '='), $safeBase64String);
    }
}