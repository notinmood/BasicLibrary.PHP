<?php
namespace Hiland\Utils\Data;

/**
 * 加解密辅助器
 *
 * @author devel
 *
 */
class CipherHelper
{

    /**
     * 为内容生成签名
     * @param string $content 签名内容
     * @param string $key 签名秘钥
     * @param string $algorithm 签名算法（默认使用md5）
     * @throws \Exception
     * @return string
     */
    public static function signature($content, $key = '', $algorithm = 'md5')
    {
        try {
            if (null == $content) {
                throw new \Exception("签名内容不能为空" . "<br>");
            }

            $signStr = $content;
            if (!empty($key)) {
                $signStr .= "&key=" . $key;
            }

            // return strtoupper(md5($signStr));
            return strtoupper(call_user_func($algorithm, $signStr));
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * 验证签名是否正确
     * @param string $content 签名内容
     * @param string $key 签名秘钥
     * @param string $signature 待验证的签名
     * @param string $algorithm 签名算法（默认使用md5）
     * @return boolean
     */
    public static function verifySignature($content, $signature, $key = '', $algorithm = 'md5')
    {
        $signStr = $content;
        if (!empty($key)) {
            $signStr .= "&key=" . $key;
        }
        $calculateSign = strtoupper(call_user_func($algorithm, $signStr));
        $signature = strtoupper($signature);
        return $calculateSign == $signature;
    }

    /**
     * 加密
     * @param string $string 待加密字符串
     * @param string $key 加密秘钥
     * @param int $expiry 过期时间
     * @param bool $safeBase64 是否使用安全的base64编码（如果直接使用base64_encode和base64_decode方法的话，生成的字符串可能不适用URL地址。）
     * @return string 加密后的字符串
     */
    public static function encrypt($string, $key = '', $expiry = 0, $safeBase64 = true)
    {
        $result = self::cipherCode($string, 'ENCODE', $key, $expiry);
        if ($safeBase64) {
            $result = str_replace(array('+', '/', '='), array('-', '_', ''), $result);
        }
        return $result;
    }

    /**
     * 对字符串进行加解密操作
     * @param string $string
     * @param string $operation 取值'DECODE'表示解密，其他字符表示加密
     * @param string $key
     * @param int $expiry
     * @return string
     */
    private static function cipherCode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;

        // 密匙
        $key = md5($key ? $key : 'seaguall-20160215');

        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) :
            substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
        //解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
            sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // 验证数据有效性，请看未加密明文的格式
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
                substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)
            ) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * 解密
     * @param string $string 待解密字符串
     * @param string $key 解密秘钥
     * @param int $expiry 过期时间
     * @param bool $safeBase64 是否使用安全的base64编码（如果直接使用base64_encode和base64_decode方法的话，生成的字符串可能不适用URL地址。）
     * @return string 解密后的字符串
     */
    public static function decrypt($string, $key = '', $expiry = 0, $safeBase64 = true)
    {
        if ($safeBase64) {
            $string = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($string) % 4;
            if ($mod4) {
                $string .= substr('====', $mod4);
            }
        }

        return self::cipherCode($string, 'DECODE', $key, $expiry);
    }
}

?>