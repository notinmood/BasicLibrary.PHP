<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
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
    /**获取安全的base64编码（用于在url中传递等工作）
     * Base64编码中使用了 两个特殊字符 + / （加除，加粗）    可以谐音为 “贝斯是加粗”的吉他
     * 为了安全起见,(在url等场景中)需要将这两个字符进行合理的替换
     * @param $data
     * @return array|string|string[]
     */
    public static function getSafeBase64($data)
    {
        $string = str_replace(array('-', '_'), array('+', '/'), $data);
        $mod4 = strlen($string) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }

        return $string;
    }

    /**
     * 从安全的base64编码，还原原始的base64编码
     * @param $data
     * @return array|string|string[]
     */
    public static function getOriginalBase64($data)
    {
        return str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    }
}