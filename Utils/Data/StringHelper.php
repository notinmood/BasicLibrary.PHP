<?php

namespace Hiland\Utils\Data;

use Hiland\Utils\Environment\EnvHelper;

class StringHelper
{
    /**
     * 获取目标编码类型的文本
     * @param string $stringData
     * @param string $targetEncoding
     * @return false|string|string[]|null
     */
    public static function getEncodingContent($stringData, $targetEncoding = 'UTF-8')
    {
        $originalEncoding = self::getEncoding($stringData);

        $result = "";
        if ($originalEncoding) {
            $result = mb_convert_encoding($stringData, $targetEncoding, $originalEncoding);
        }

        return $result;
    }

    /**
     * 获取内容的编码
     * @param string $stringData
     * @return bool|string
     * 备注1：php中用mb_detect_encoding测出来的euc-cn是gb2312编码：
     * EUC-CN是GB2312最常用的表示方法。浏览器编码表上的“GB2312”，通常都是指“EUC-CN”表示法。
     * 备注2：用mb_detect_encoding函数进行编码识别时，很多人都碰到过识别编码有误问题的说明
     * https://www.jb51.net/article/27282.htm
     */
    public static function getEncoding($stringData = "")
    {
        return mb_detect_encoding($stringData, array("ASCII", "GB2312", "GBK", "UTF-8"));
    }

    /**
     * 获取文本文件的回车换行符
     * @return string
     */
    public static function getNewLineSymbol()
    {
        return EnvHelper::getNewLineSymbol();
    }

    /**
     * 截取全角和半角（汉字和英文）混合的字符串以避免乱码
     * @param string $originalStringData
     *            要截取的字符串
     * @param int    $startPosition
     *            开始位置(第一个字符的位置为0)
     * @param int    $length
     *            要截取的长度(超过总长度 按总长度计算)
     * @param string $charset
     * @return string
     * @author 小墨 244349067@qq.com
     */
    public static function subString($originalStringData, $startPosition, $length = 0, $charset = "utf-8")
    {
        $originalStringLength = strlen($originalStringData);

        if ($startPosition >= $originalStringLength) {
            return '';
        }

        if ($length > $originalStringLength - $startPosition) {
            $length = $originalStringLength - $startPosition;
        }

        if ($length == 0) {
            $length = $originalStringLength - $startPosition;
        }

        if (function_exists("mb_substr")) {
            $slice = mb_substr($originalStringData, $startPosition, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($originalStringData, $startPosition, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $originalStringData, $match);
            $slice = join("", array_slice($match[0], $startPosition, $length));
        }

        return $slice;
    }

    /**
     * @param string $paddingStringData 待测试的结尾字符
     * @param string $wholeStringData   全句
     * @return bool
     */
    public static function isEndWith($wholeStringData, $paddingStringData)
    {
        $paddingLength = strlen($paddingStringData);
        $fullLength = strlen($wholeStringData);
        $subString = substr($wholeStringData, $fullLength - $paddingLength);
        if ($subString == $paddingStringData) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $paddingStringData 待测试的开始字符
     * @param string $wholeStringData   全句
     * @return bool
     */
    public static function isStartWith($wholeStringData, $paddingStringData)
    {
        $before = self::getStringBeforeSeperator($wholeStringData, $paddingStringData);
        if ($before == '') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断一个字符串是否被包含在另外一个字符串内
     * @param string $subStringData
     *            被查找的子字符串
     * @param string $wholeStringData
     *            查找的母体字符串
     * @return boolean
     */
    public static function isContains($wholeStringData, $subStringData)
    {
        $result = strstr($wholeStringData, $subStringData);

        if ($result === false) {
            return false;
        } else {
            return true;
        }
    }

    /**获取字符串长度
     * @param string $stringData
     * @param string $encoding
     * @return false|int
     */
    public static function getLength($stringData, $encoding = "utf-8")
    {
        return mb_strlen($stringData, $encoding);
    }

    /**包装php的字符串替换(将各个参数名称进一步明确化)
     * @param string $wholeStringData
     * @param string $oldStringDataOrRegex
     * @param string $newStringData
     * @param bool   $useRegex
     * @return array|string|string[]
     */
    public static function replace($wholeStringData, $oldStringDataOrRegex, $newStringData, $useRegex = false)
    {
        if ($useRegex) {
            return preg_replace($oldStringDataOrRegex, $newStringData, $wholeStringData);
        } else {
            return str_replace($oldStringDataOrRegex, $newStringData, $wholeStringData);
        }
    }

    /**
     * 将一个字符串按照某个分隔符分隔成数组
     * @param $wholeStringData     string 字符串全串
     * @param $delimiter           string 分隔符
     * @return false|string[]
     */
    public static function explode($wholeStringData, $delimiter)
    {
        return explode($delimiter, $wholeStringData);
    }

    /**
     * 将一个数组的各个元素串联成一个字符串
     * @param $arrayData
     * @param $delimiter
     * @return string
     */
    public static function implode($arrayData, $delimiter = "")
    {
        return implode($delimiter, $arrayData);
    }

    /**
     * 将一个字符串按照字符个数分组进行格式化
     * @param string $stringData string
     * @param string $formator   string 字符串字符个数分组的格式，同一个分组内字符的个数用{}包围，各个分组之间可以自定义分隔符，例如
     *                           '{4}-{2}-{2}'，或者'{4} {2} {2}'(中间用空格表示);
     * @return string
     */
    public static function grouping($stringData, $formator)
    {
        $content = '';
        $pattern = '/{\d*}/';
        $matches = null;
        $result = preg_match_all($pattern, $formator, $matches);
        if ($result) {
            foreach ($matches[0] as $matchedWithQuotation) {
                $matchedWithQuotationStartPosition = strpos($formator, $matchedWithQuotation);
                $matchedWithQuotationLength = strlen($matchedWithQuotation);
                $seperator = substr($formator, 0, $matchedWithQuotationStartPosition);
                $content .= $seperator;
                $seperatorLength = strlen($seperator);
                $formator = substr($formator, $matchedWithQuotationLength + $seperatorLength);

                $matchedNumber = StringHelper::getStringAfterSeperator($matchedWithQuotation, '{');
                $matchedNumber = StringHelper::getStringBeforeSeperator($matchedNumber, '}');
                $matchedNumber = (int)$matchedNumber;
                $dataLength = strlen($stringData);
                if ($dataLength >= $matchedNumber) {
                    $content .= substr($stringData, 0, $matchedNumber);
                    $stringData = substr($stringData, $matchedNumber);
                } else {
                    $content .= $stringData;
                    $stringData = '';
                }
            }
        }
        return $content;
    }


    /** 对带有占位符的字符串信息，进行格式化填充，形成完整的字符串。
     * 现在推荐直接使用 PHP系统自带的格式化方式,例如:"k的值为{$k}；v的值为{$v}"
     * @param $stringData          string 带有占位符的字符串信息（占位符用{?}表示），例如 "i like this {?},do you known {?}"
     * @param $realValueList       string[] 待填入的真实信息，用字符串数组表示，例如["qingdao","beijing"];
     *                             或者使用用逗号分隔的各个独立的字符串表示,比如"qingdao","beijing"
     * @return string
     */
    public static function format($stringData, ...$realValueList)
    {
        $needle = "{?}";
        // 查找?位置
        $p = strpos($stringData, $needle);
        // 替换字符的数组下标
        $i = 0;

        if (ObjectHelper::getLength($realValueList) == 1 && ObjectHelper::getType($realValueList[0]) == ObjectTypes::ARRAYS) {
            $realValueList = $realValueList[0];
        }

        while ($p !== false) {
            $stringData = substr_replace($stringData, $realValueList[$i++], $p, 3);
            // 查找下一个?位置  没有时会退出循环
            $p = strpos($stringData, $needle, ++$p);
        }

        return $stringData;
    }


    /**
     * 获取字符串分隔符前面的内容
     * @param string $wholeStringData
     * @param string $seperator
     * @return string
     */
    public static function getStringBeforeSeperator($wholeStringData, $seperator)
    {
        if (self::isContains($wholeStringData, $seperator)) {
            $array = explode($seperator, $wholeStringData);
            return $array[0];
        } else {
            return $wholeStringData;
        }
    }

    /**
     * 获取字符串分隔符后面的内容
     * @param string $wholeStringData
     * @param string $seperator
     * @return string
     */
    public static function getStringAfterSeperator($wholeStringData, $seperator)
    {
        if (self::isContains($wholeStringData, $seperator)) {
            $array = explode($seperator, $wholeStringData);
            return $array[1];
        } else {
            return $wholeStringData;
        }
    }

    /**
     * 将字符串中第一个单词的首字母大写
     * @param $stringData
     * @return string
     */
    public static function upperStringFirstChar($stringData)
    {
        return ucfirst($stringData);
    }

    /**
     * 将字符串中每一个单词的首字母大写
     * @param $stringData
     * @return string
     */
    public static function upperWordsFirstChar($stringData)
    {
        return ucwords($stringData);
    }

    /**
     * 将字符串中每一个字母都转成大写
     * @param $stringData
     * @return string
     */
    public static function upper($stringData)
    {
        return mb_strtoupper($stringData);
    }

    /**
     * 将字符串中每一个字母都转成小写
     * @param $stringData
     * @return string
     */
    public static function lower($stringData)
    {
        return mb_strtolower($stringData);
    }

    /**
     * @param string $stringData 需要进行补充的原始字符串
     * @param int    $length
     * @param string $pad
     * @return string
     */
    public static function paddingEnd($stringData, $length, $pad = " ")
    {
        return str_pad($stringData, $length, $pad);
    }

    /**
     * @param string $stringData 需要进行补充的原始字符串
     * @param int    $length
     * @param string $pad
     * @return string
     */
    public static function paddingBegin($stringData, $length, $pad = " ")
    {
        return str_pad($stringData, $length, $pad, STR_PAD_LEFT);
    }

    /**
     * 翻转字符串
     * @param string $stringData 待翻转的字符串
     * @return string
     */
    public static function reverse($stringData)
    {
        return strrev($stringData);
    }

    // /**
    //  * 将UTF8字符串转换成Unicode字符串
    //  * @param $utf8StringData
    //  * @return string
    //  */
    // public static function convertFromUTF8ToUnicode($utf8StringData)
    // {
    //     $length = self::getLength($utf8StringData);
    //     $result = [];
    //     for ($i = 0; $i < $length; $i++) {
    //         $result[] = self::convertCharFromUTF8ToUnicode($utf8StringData[$i]);
    //     }
    //
    //     return self::implode($result);
    // }

    // /**
    //  * Unicode字符串转换成utf8字符串
    //  * @param $unicodeStringData
    //  * @return string
    //  */
    // public static function convertFromUnicodeToUTF8($unicodeStringData){
    //     $length = self::getLength($unicodeStringData);
    //     $result = [];
    //     for ($i = 0; $i < $length; $i++) {
    //         $result[] = self::convertCharFromUnicodeToUTF8($unicodeStringData[$i]);
    //     }
    //
    //     return self::implode($result);
    // }

    /**
     * utf8字符转换成Unicode字符
     * @param $utf8Char string
     * @return string      Unicode字符
     */
    public static function convertCharFromUTF8ToUnicode($utf8Char)
    {
        $unicode = 0;
        $unicode = (ord($utf8Char[0]) & 0x1F) << 12;
        $unicode |= (ord($utf8Char[1]) & 0x3F) << 6;
        $unicode |= (ord($utf8Char[2]) & 0x3F);
        return dechex($unicode);
    }

    /**
     * Unicode字符转换成utf8字符
     * @param string $unicodeChar Unicode字符
     * @return string       Utf-8字符
     */
    public static function convertCharFromUnicodeToUTF8($unicodeChar)
    {
        $code = intval(hexdec($unicodeChar));
        //这里注意转换出来的code一定得是整形，这样才会正确的按位操作
        $ord_1 = decbin(0xe0 | ($code >> 12));
        $ord_2 = decbin(0x80 | (($code >> 6) & 0x3f));
        $ord_3 = decbin(0x80 | ($code & 0x3f));
        return chr(bindec($ord_1)) . chr(bindec($ord_2)) . chr(bindec($ord_3));
    }
}